<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TrackingMoreService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.trackingmore.api_key');
        $this->baseUrl = config('services.trackingmore.base_url');
    }

    /**
     * Get HTTP client with headers
     */
    private function getClient()
    {
        return Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'Tracking-Api-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->timeout(30);
    }

    /**
     * Detect courier dari nomor resi
     */
    public function detectCourier($trackingNumber)
    {
        try {
            $response = $this->getClient()->post('/couriers/detect', [
                'tracking_number' => $trackingNumber,
            ]);

            $result = $response->json();

            Log::info('TrackingMore Detect Courier', [
                'tracking_number' => $trackingNumber,
                'response' => $result,
            ]);

            if ($response->successful() && $result['meta']['code'] == 200 && !empty($result['data'])) {
                return [
                    'success' => true,
                    'couriers' => $result['data'],
                ];
            }

            return [
                'success' => false,
                'message' => $result['meta']['message'] ?? 'Courier not detected',
            ];
        } catch (\Exception $e) {
            Log::error('TrackingMore Detect Courier Error', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create tracking (register shipment)
     */
    public function createTracking($trackingNumber, $courierCode)
    {
        try {
            $response = $this->getClient()->post('/trackings/create', [
                'tracking_number' => $trackingNumber,
                'courier_code' => $courierCode,
            ]);

            $result = $response->json();

            Log::info('TrackingMore Create Tracking', [
                'tracking_number' => $trackingNumber,
                'courier_code' => $courierCode,
                'response' => $result,
            ]);

            if ($response->successful() && $result['meta']['code'] == 200) {
                return [
                    'success' => true,
                    'data' => $result['data'],
                ];
            }

            return [
                'success' => false,
                'message' => $result['meta']['message'] ?? 'Failed to create tracking',
            ];
        } catch (\Exception $e) {
            Log::error('TrackingMore Create Tracking Error', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get tracking info (real-time update)
     */
    public function getTracking($trackingNumber, $courierCode)
    {
        try {
            $response = $this->getClient()->get("/trackings/{$courierCode}/{$trackingNumber}");

            $result = $response->json();

            Log::info('TrackingMore Get Tracking', [
                'tracking_number' => $trackingNumber,
                'courier_code' => $courierCode,
                'response' => $result,
            ]);

            if ($response->successful() && $result['meta']['code'] == 200) {
                return [
                    'success' => true,
                    'data' => $result['data'],
                ];
            }

            return [
                'success' => false,
                'message' => $result['meta']['message'] ?? 'Tracking not found',
            ];
        } catch (\Exception $e) {
            Log::error('TrackingMore Get Tracking Error', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete tracking (jika salah input resi)
     */
    public function deleteTracking($trackingNumber, $courierCode)
    {
        try {
            $response = $this->getClient()->delete("/trackings/{$courierCode}/{$trackingNumber}");

            Log::info('TrackingMore Delete Tracking', [
                'tracking_number' => $trackingNumber,
                'courier_code' => $courierCode,
                'success' => $response->successful(),
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Tracking deleted successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to delete tracking',
            ];
        } catch (\Exception $e) {
            Log::error('TrackingMore Delete Tracking Error', [
                'tracking_number' => $trackingNumber,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Parse tracking events ke format yang konsisten
     */
    public function parseTrackingEvents($trackingData)
    {
        if (empty($trackingData['origin_info']['trackinfo'])) {
            return [];
        }

        $events = [];
        foreach ($trackingData['origin_info']['trackinfo'] as $track) {
            $events[] = [
                'status' => $this->mapStatus($track['StatusDescription'] ?? ''),
                'description' => $track['StatusDescription'] ?? 'Update status',
                'location' => $track['Details'] ?? null,
                'tracked_at' => $track['Date'] ?? now(),
            ];
        }

        return $events;
    }

    /**
     * Map status dari TrackingMore ke status internal
     */
    private function mapStatus($statusDescription)
    {
        $statusDescription = strtolower($statusDescription);

        if (str_contains($statusDescription, 'delivered')) {
            return 'delivered';
        } elseif (str_contains($statusDescription, 'transit') || str_contains($statusDescription, 'on the way')) {
            return 'in_transit';
        } elseif (str_contains($statusDescription, 'picked') || str_contains($statusDescription, 'received')) {
            return 'picked_up';
        }

        return 'pending';
    }
}
