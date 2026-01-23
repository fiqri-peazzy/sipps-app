<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.fonnte.api_key');
        $this->baseUrl = config('services.fonnte.base_url');

        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
        ]);
    }

    /**
     * Kirim notifikasi pengiriman ke customer
     */
    public function sendShippingNotification($phoneNumber, $orderNumber, $resi, $kurir)
    {
        try {
            $message = $this->formatShippingMessage($orderNumber, $resi, $kurir);

            // Placeholder - akan diimplementasikan nanti dengan Fonnte API
            Log::info('WhatsApp Notification (Placeholder)', [
                'phone' => $phoneNumber,
                'order_number' => $orderNumber,
                'resi' => $resi,
                'message' => $message,
            ]);

            // TODO: Implementasi Fonnte API
            /*
            $response = $this->client->post('/send', [
                'headers' => [
                    'Authorization' => $this->apiKey,
                ],
                'form_params' => [
                    'target' => $phoneNumber,
                    'message' => $message,
                ],
            ]);
            */

            return [
                'success' => true,
                'message' => 'Notification logged (placeholder)',
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Notification Error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Kirim update status pengiriman
     */
    public function sendTrackingUpdate($phoneNumber, $orderNumber, $status, $description, $location = null)
    {
        try {
            $message = $this->formatTrackingUpdateMessage($orderNumber, $status, $description, $location);

            Log::info('WhatsApp Tracking Update (Placeholder)', [
                'phone' => $phoneNumber,
                'order_number' => $orderNumber,
                'status' => $status,
                'message' => $message,
            ]);

            // TODO: Implementasi Fonnte API

            return [
                'success' => true,
                'message' => 'Notification logged (placeholder)',
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp Tracking Update Error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Format message untuk notifikasi pengiriman
     */
    private function formatShippingMessage($orderNumber, $resi, $kurir)
    {
        return "*Pesanan Anda Sedang Dikirim!* 📦

Nomor Order: *{$orderNumber}*
Kurir: *{$kurir}*
No. Resi: *{$resi}*

Pesanan Anda sedang dalam perjalanan. Silakan pantau status pengiriman melalui website kami.

Terima kasih telah berbelanja! 🙏";
    }

    /**
     * Kirim notifikasi pengajuan return
     */
    public function sendReturnRequestNotification($phoneNumber, $orderNumber, $itemName)
    {
        try {
            $message = "*Pengajuan Pengembalian (Return) Diterima* 🔄\n\nNomor Order: *{$orderNumber}*\nItem: *{$itemName}*\n\nStatus: *Menunggu Review Admin*\n\nKami telah menerima pengajuan pengembalian Anda. Admin kami akan segera meninjau bukti yang Anda kirimkan. Mohon tunggu informasi selanjutnya.";

            Log::info('WhatsApp Return Request (Placeholder)', [
                'phone' => $phoneNumber,
                'order_number' => $orderNumber,
                'message' => $message,
            ]);

            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('WhatsApp Return Request Error', ['phone' => $phoneNumber, 'error' => $e->getMessage()]);
            return ['success' => false];
        }
    }

    /**
     * Kirim notifikasi return disetujui
     */
    public function sendReturnApprovalNotification($phoneNumber, $orderNumber, $itemName, $notes = null)
    {
        try {
            $message = "*Pengajuan Return Disetujui* ✅\n\nNomor Order: *{$orderNumber}*\nItem: *{$itemName}*\n\nStatus: *Disetujui*\nCatatan Admin: _" . ($notes ?? 'Pesanan pengganti sedang diproses') . "_\n\nKabar baik! Pengajuan return Anda telah disetujui. Kami sedang memproses item pengganti untuk Anda dan akan segera diproduksi ulang.";

            Log::info('WhatsApp Return Approval (Placeholder)', [
                'phone' => $phoneNumber,
                'order_number' => $orderNumber,
                'message' => $message,
            ]);

            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('WhatsApp Return Approval Error', ['phone' => $phoneNumber, 'error' => $e->getMessage()]);
            return ['success' => false];
        }
    }

    /**
     * Kirim notifikasi return ditolak
     */
    public function sendReturnRejectionNotification($phoneNumber, $orderNumber, $itemName, $reason)
    {
        try {
            $message = "*Pengajuan Return Ditolak* ❌\n\nNomor Order: *{$orderNumber}*\nItem: *{$itemName}*\n\nStatus: *Ditolak*\nAlasan: _" . $reason . "_\n\nMohon maaf, pengajuan return Anda belum dapat kami setujui. Jika ada pertanyaan lebih lanjut, silakan hubungi admin.";

            Log::info('WhatsApp Return Rejection (Placeholder)', [
                'phone' => $phoneNumber,
                'order_number' => $orderNumber,
                'message' => $message,
            ]);

            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('WhatsApp Return Rejection Error', ['phone' => $phoneNumber, 'error' => $e->getMessage()]);
            return ['success' => false];
        }
    }

    /**
     * Format message untuk update tracking
     */
    private function formatTrackingUpdateMessage($orderNumber, $status, $description, $location)
    {
        $statusLabel = [
            'picked_up' => '📦 Paket Diambil Kurir',
            'in_transit' => '🚚 Dalam Perjalanan',
            'delivered' => '✅ Paket Telah Sampai',
        ][$status] ?? '📍 Update Status';

        $message = "*Update Pengiriman* - {$statusLabel}

Nomor Order: *{$orderNumber}*
Status: {$description}";

        if ($location) {
            $message .= "\nLokasi: {$location}";
        }

        return $message;
    }
}
