<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = config('services.midtrans.is_sanitized');
        Config::$is3ds = config('services.midtrans.is_3ds');
    }

    /**
     * Generate Snap Token untuk pembayaran
     */
    public function createTransaction(Order $order)
    {
        try {
            // Item details
            $itemDetails = [];
            foreach ($order->items as $item) {
                $itemDetails[] = [
                    'id' => $item->produk_id,
                    'price' => (int) $item->harga_satuan,
                    'quantity' => $item->quantity,
                    'name' => $item->produk->jenisSablon->nama . ' - ' . $item->produk->ukuran->nama,
                ];
            }

            // Ongkir sebagai item terpisah
            if ($order->ongkir > 0) {
                $itemDetails[] = [
                    'id' => 'ONGKIR',
                    'price' => (int) $order->ongkir,
                    'quantity' => 1,
                    'name' => 'Ongkos Kirim - ' . $order->kurir,
                ];
            }

            // Generate unique order_id dengan timestamp untuk menghindari 409 conflict
            $uniqueOrderId = $order->order_number . '-' . time();

            // Transaction details
            $transactionDetails = [
                'order_id' => $uniqueOrderId,
                'gross_amount' => (int) $order->total_harga,
            ];

            // Customer details
            $customerDetails = [
                'first_name' => $order->penerima_nama,
                'email' => $order->user->email,
                'phone' => $order->penerima_telepon,
                'shipping_address' => [
                    'first_name' => $order->penerima_nama,
                    'phone' => $order->penerima_telepon,
                    'address' => $order->alamat_lengkap,
                    'city' => $order->kota,
                    'postal_code' => $order->kode_pos,
                ],
            ];

            // Expiry settings (24 jam)
            $expirySettings = [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hour',
                'duration' => 24,
            ];

            // Payment params
            $params = [
                'transaction_details' => $transactionDetails,
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
                'expiry' => $expirySettings,
                'enabled_payments' => [
                    'credit_card',
                    'bca_va',
                    'bni_va',
                    'bri_va',
                    'permata_va',
                    'other_va',
                    'gopay',
                    'shopeepay',
                    'qris',
                ],
                'callbacks' => [
                    'finish' => route('customer.payment.finish') . '?order_id=' . $uniqueOrderId,
                    'unfinish' => route('customer.payment.unfinish') . '?order_id=' . $uniqueOrderId,
                    'error' => route('customer.payment.error') . '?order_id=' . $uniqueOrderId,
                ],
            ];

            // Generate snap token
            $snapToken = Snap::getSnapToken($params);

            // Update order dengan snap token dan expired time
            $order->update([
                'snap_token' => $snapToken,
                'payment_expired_at' => now()->addHours(24),
                'payment_status' => 'pending',
            ]);

            Log::info('Snap Token Generated', [
                'order_number' => $order->order_number,
                'unique_order_id' => $uniqueOrderId,
                'snap_token' => $snapToken,
            ]);

            return [
                'success' => true,
                'snap_token' => $snapToken,
                'unique_order_id' => $uniqueOrderId,
            ];
        } catch (\Exception $e) {
            Log::error('Midtrans Create Transaction Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle notification dari Midtrans
     */
    public function handleNotification($notification)
    {
        try {
            $notif = new Notification();

            $transactionStatus = $notif->transaction_status;
            $fraudStatus = $notif->fraud_status ?? null;
            $orderIdFromMidtrans = $notif->order_id;
            $transactionId = $notif->transaction_id;
            $paymentType = $notif->payment_type;
            $grossAmount = $notif->gross_amount;

            // Extract order_number asli (hapus suffix timestamp)
            $orderNumber = preg_replace('/-\d+$/', '', $orderIdFromMidtrans);

            Log::info('Midtrans Notification Received', [
                'order_id_midtrans' => $orderIdFromMidtrans,
                'order_number_extracted' => $orderNumber,
                'transaction_status' => $transactionStatus,
                'transaction_id' => $transactionId,
                'fraud_status' => $fraudStatus,
            ]);

            // Find order
            $order = Order::where('order_number', $orderNumber)->first();

            if (!$order) {
                Log::error('Order not found', ['order_number' => $orderNumber]);
                return ['success' => false, 'message' => 'Order not found'];
            }

            // Save payment history
            $order->paymentHistories()->create([
                'transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'gross_amount' => $grossAmount,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'midtrans_response' => json_decode(json_encode($notif), true),
            ]);

            // Update order status based on transaction status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $this->updateOrderStatus($order, 'paid', $transactionId, $paymentType, 'capture');
                }
            } elseif ($transactionStatus == 'settlement') {
                $this->updateOrderStatus($order, 'paid', $transactionId, $paymentType, 'settlement');
            } elseif ($transactionStatus == 'pending') {
                $order->update([
                    'payment_status' => 'pending',
                    'transaction_id' => $transactionId,
                ]);
            } elseif ($transactionStatus == 'deny') {
                $order->update([
                    'payment_status' => 'deny',
                    'transaction_id' => $transactionId,
                ]);
            } elseif ($transactionStatus == 'expire') {
                $order->update([
                    'payment_status' => 'expire',
                    'transaction_id' => $transactionId,
                ]);
            } elseif ($transactionStatus == 'cancel') {
                $order->update([
                    'payment_status' => 'cancel',
                    'transaction_id' => $transactionId,
                ]);
            }

            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Update order status to paid
     */
    private function updateOrderStatus($order, $status, $transactionId, $paymentType, $paymentStatus)
    {
        $order->update([
            'status' => $status,
            'payment_status' => $paymentStatus,
            'transaction_id' => $transactionId,
            'metode_pembayaran' => $paymentType,
            'paid_at' => now(),
        ]);

        Log::info('Order payment success', [
            'order_number' => $order->order_number,
            'transaction_id' => $transactionId,
            'payment_status' => $paymentStatus,
        ]);
    }

    /**
     * Get transaction status dari Midtrans
     */
    public function getTransactionStatus($orderId)
    {
        try {
            $status = Transaction::status($orderId);

            Log::info('Get Transaction Status', [
                'order_id' => $orderId,
                'transaction_status' => $status->transaction_status,
                'transaction_id' => $status->transaction_id ?? null,
            ]);

            return [
                'success' => true,
                'data' => $status,
            ];
        } catch (\Exception $e) {
            Log::error('Get Transaction Status Error', [
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
