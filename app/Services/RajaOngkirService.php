<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl = 'https://rajaongkir.komerce.id/api/v1';

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.api_key');
    }

    /**
     * Get list provinsi
     */
    public function getProvinces()
    {
        return Cache::remember('rajaongkir_provinces', 86400, function () {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                    'accept' => 'application/json',
                ])->get($this->baseUrl . '/destination/province');

                if ($response->json('meta.status') == 'success' && $response->json('meta.code') == 200) {
                    return $response->json('data');
                }

                return [];
            } catch (\Exception $e) {
                Log::error('RajaOngkir Get Provinces Error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get list kota by province
     */
    public function getCities($provinceId = null)
    {
        $cacheKey = $provinceId ? "rajaongkir_cities_{$provinceId}" : "rajaongkir_cities_all";

        return Cache::remember($cacheKey, 86400, function () use ($provinceId) {
            try {
                $url = $this->baseUrl . '/destination/city';
                if ($provinceId) {
                    $url .= '/' . urlencode($provinceId);
                }

                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                    'accept' => 'application/json',
                ])->get($url);

                if ($response->json('meta.status') == 'success' && $response->json('meta.code') == 200) {
                    return $response->json('data');
                }

                return [];
            } catch (\Exception $e) {
                Log::error('RajaOngkir Get Cities Error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get city detail by ID
     */
    public function getCityById($cityId)
    {
        $cacheKey = "rajaongkir_city_{$cityId}";

        return Cache::remember($cacheKey, 86400, function () use ($cityId) {
            try {
                $response = Http::withHeaders([
                    'key' => $this->apiKey
                ])->get($this->baseUrl . '/city', [
                    'id' => $cityId
                ]);

                if ($response->successful() && $response->json('rajaongkir.status.code') == 200) {
                    return $response->json('rajaongkir.results');
                }

                return null;
            } catch (\Exception $e) {
                Log::error('RajaOngkir Get City By ID Error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Calculate shipping cost
     */
    public function calculateCost($origin, $destination, $weight, $courier = 'all')
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey
            ])->post($this->baseUrl . '/cost', [
                'origin' => $origin,
                'originType' => 'city',
                'destination' => $destination,
                'destinationType' => 'city',
                'weight' => $weight,
                'courier' => $courier
            ]);

            Log::info('RajaOngkir Calculate Cost Request', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courier
            ]);

            if ($response->successful() && $response->json('rajaongkir.status.code') == 200) {
                $results = $response->json('rajaongkir.results');

                // Format response untuk kemudahan penggunaan
                $formattedResults = [];

                foreach ($results as $courierData) {
                    if (isset($courierData['costs']) && is_array($courierData['costs'])) {
                        foreach ($courierData['costs'] as $service) {
                            $formattedResults[] = [
                                'name' => $courierData['name'],
                                'code' => $courierData['code'],
                                'service' => $service['service'],
                                'description' => $service['description'],
                                'cost' => $service['cost'][0]['value'] ?? 0,
                                'etd' => $service['cost'][0]['etd'] ?? '',
                            ];
                        }
                    }
                }

                // Sort by cost ascending
                usort($formattedResults, function ($a, $b) {
                    return $a['cost'] <=> $b['cost'];
                });

                Log::info('RajaOngkir Calculate Cost Success', [
                    'total_options' => count($formattedResults)
                ]);

                return [
                    'success' => true,
                    'data' => $formattedResults
                ];
            }

            Log::error('RajaOngkir Calculate Cost Failed', [
                'response' => $response->json()
            ]);

            return [
                'success' => false,
                'message' => 'Gagal menghitung ongkir',
                'data' => []
            ];
        } catch (\Exception $e) {
            Log::error('RajaOngkir Calculate Cost Error: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Search city by name
     */
    public function searchCity($keyword)
    {
        $cities = $this->getCities();

        return array_filter($cities, function ($city) use ($keyword) {
            $cityName = strtolower($city['city_name']);
            $type = strtolower($city['type']);
            $searchTerm = strtolower($keyword);

            return strpos($cityName, $searchTerm) !== false ||
                strpos($type, $searchTerm) !== false;
        });
    }

    /**
     * Get Gorontalo city ID
     */
    public function getGorontaloCityId()
    {
        $cities = $this->getCities();

        foreach ($cities as $city) {
            if (
                strtolower($city['city_name']) == 'gorontalo' &&
                strtolower($city['type']) == 'kota'
            ) {
                return $city['city_id'];
            }
        }

        return null;
    }
}
