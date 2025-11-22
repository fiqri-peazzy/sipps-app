<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Initiate payment - Generate Snap Token
     */
    public function initiatePayment(Order $order)
    {
        // Validasi: pastikan order milik user yang login
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access',
            ], 403);
        }

        // Validasi: order harus status pending_payment
        if ($order->status !== 'pending_payment') {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak dalam status menunggu pembayaran',
            ], 400);
        }

        // Validasi: payment belum expired
        if ($order->payment_expired_at && now()->greaterThan($order->payment_expired_at)) {
            $order->update(['payment_status' => 'expire']);

            return response()->json([
                'success' => false,
                'message' => 'Waktu pembayaran telah habis',
            ], 400);
        }

        // Generate snap token baru setiap kali
        $result = $this->midtrans->createTransaction($order);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'snap_token' => $result['snap_token'],
                'unique_order_id' => $result['unique_order_id'],
                'client_key' => config('services.midtrans.client_key'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Gagal membuat transaksi pembayaran',
        ], 500);
    }

    /**
     * Handle notification callback dari Midtrans
     */
    public function callback(Request $request)
    {
        Log::info('Midtrans Callback Received', $request->all());

        $result = $this->midtrans->handleNotification($request->all());

        if ($result['success']) {
            return response()->json(['message' => 'OK'], 200);
        }

        return response()->json(['message' => 'Failed'], 500);
    }

    /**
     * Handle finish redirect (success payment)
     */
    public function finish(Request $request)
    {
        $orderIdFromMidtrans = $request->get('order_id');

        Log::info('Payment Finish Called', [
            'order_id_from_url' => $orderIdFromMidtrans,
            'all_params' => $request->all(),
        ]);

        if (!$orderIdFromMidtrans) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Order tidak ditemukan');
        }

        // Extract order_number asli (hapus suffix timestamp)
        $orderNumber = preg_replace('/-\d+$/', '', $orderIdFromMidtrans);

        Log::info('Order Number Extracted', [
            'order_id_midtrans' => $orderIdFromMidtrans,
            'order_number_clean' => $orderNumber,
        ]);

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            Log::error('Order not found in finish', [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('customer.orders.index')
                ->with('error', 'Order tidak ditemukan');
        }

        // CRITICAL: Manual check status dari Midtrans API
        try {
            Log::info('Checking payment status from Midtrans', [
                'order_id_for_check' => $orderIdFromMidtrans,
            ]);

            $statusResult = $this->midtrans->getTransactionStatus($orderIdFromMidtrans);

            if ($statusResult['success']) {
                $status = $statusResult['data'];

                $transactionStatus = $status->transaction_status;
                $paymentType = $status->payment_type ?? null;
                $transactionId = $status->transaction_id ?? null;
                $fraudStatus = $status->fraud_status ?? null;

                Log::info('Payment Status Retrieved', [
                    'order_number' => $orderNumber,
                    'transaction_status' => $transactionStatus,
                    'transaction_id' => $transactionId,
                    'payment_type' => $paymentType,
                ]);

                // Update berdasarkan status
                if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $fraudStatus == 'accept')) {

                    // Update order ke paid
                    $order->update([
                        'status' => 'paid',
                        'payment_status' => $transactionStatus,
                        'transaction_id' => $transactionId,
                        'metode_pembayaran' => $paymentType,
                        'paid_at' => now(),
                    ]);

                    // Save payment history
                    $order->paymentHistories()->create([
                        'transaction_id' => $transactionId,
                        'payment_type' => $paymentType,
                        'gross_amount' => $status->gross_amount,
                        'transaction_status' => $transactionStatus,
                        'fraud_status' => $fraudStatus,
                        'midtrans_response' => json_decode(json_encode($status), true),
                    ]);

                    Log::info('Order payment updated to paid from finish', [
                        'order_number' => $orderNumber,
                        'transaction_id' => $transactionId,
                    ]);

                    return redirect()->route('customer.orders.show', $order->id)
                        ->with('success', 'Pembayaran berhasil! Pesanan Anda sedang diproses.');
                } elseif ($transactionStatus == 'pending') {

                    $order->update([
                        'payment_status' => 'pending',
                        'transaction_id' => $transactionId,
                    ]);

                    Log::info('Payment still pending', [
                        'order_number' => $orderNumber,
                        'transaction_id' => $transactionId,
                    ]);

                    return redirect()->route('customer.orders.show', $order->id)
                        ->with('info', 'Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
                } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'failure'])) {

                    $order->update([
                        'payment_status' => $transactionStatus,
                        'transaction_id' => $transactionId,
                    ]);

                    Log::warning('Payment failed or cancelled', [
                        'order_number' => $orderNumber,
                        'transaction_status' => $transactionStatus,
                    ]);

                    return redirect()->route('customer.orders.show', $order->id)
                        ->with('error', 'Pembayaran gagal atau dibatalkan. Silakan coba lagi.');
                }
            } else {
                Log::error('Failed to get transaction status', [
                    'order_id' => $orderIdFromMidtrans,
                    'error' => $statusResult['message'] ?? 'Unknown error',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Exception when checking payment status on finish', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'order_number' => $orderNumber,
            ]);
        }

        return redirect()->route('customer.orders.show', $order->id)
            ->with('info', 'Pembayaran sedang diproses. Silakan tunggu konfirmasi.');
    }

    /**
     * Handle unfinish redirect (user cancel payment)
     */
    public function unfinish(Request $request)
    {
        $orderIdFromMidtrans = $request->get('order_id');

        if (!$orderIdFromMidtrans) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Order tidak ditemukan');
        }

        $orderNumber = preg_replace('/-\d+$/', '', $orderIdFromMidtrans);

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Order tidak ditemukan');
        }

        Log::info('Payment unfinished', ['order_number' => $orderNumber]);

        return redirect()->route('customer.orders.show', $order->id)
            ->with('warning', 'Pembayaran dibatalkan. Silakan lakukan pembayaran kembali.');
    }

    /**
     * Handle error redirect
     */
    public function error(Request $request)
    {
        $orderIdFromMidtrans = $request->get('order_id');

        if (!$orderIdFromMidtrans) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Terjadi kesalahan pada pembayaran');
        }

        $orderNumber = preg_replace('/-\d+$/', '', $orderIdFromMidtrans);

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('customer.orders.index')
                ->with('error', 'Order tidak ditemukan');
        }

        Log::error('Payment error', ['order_number' => $orderNumber]);

        return redirect()->route('customer.orders.show', $order->id)
            ->with('error', 'Terjadi kesalahan pada pembayaran. Silakan coba lagi.');
    }

    /**
     * Check payment status
     */
    public function checkStatus(Order $order)
    {
        // Validasi ownership
        if ($order->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        if (!$order->snap_token) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found',
            ], 404);
        }

        // Gunakan order_number + timestamp terakhir yang di-generate
        // Atau gunakan transaction_id jika sudah ada
        $orderId = $order->transaction_id ?? $order->order_number;

        $result = $this->midtrans->getTransactionStatus($orderId);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'data' => [
                    'transaction_status' => $result['data']->transaction_status,
                    'payment_type' => $result['data']->payment_type ?? null,
                    'transaction_time' => $result['data']->transaction_time ?? null,
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'],
        ], 500);
    }
}
