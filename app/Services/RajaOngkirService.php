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
     * Get list district by city
     */
    public function getDistricts($cityId)
    {
        $cacheKey = "rajaongkir_districts_{$cityId}";

        return Cache::remember($cacheKey, 86400, function () use ($cityId) {
            try {
                $url = $this->baseUrl . '/destination/district/' . urlencode($cityId);

                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                    'accept' => 'application/json',
                ])->get($url);

                if ($response->json('meta.status') == 'success' && $response->json('meta.code') == 200) {
                    return $response->json('data');
                }

                return [];
            } catch (\Exception $e) {
                Log::error('RajaOngkir Get Districts Error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Get list subdistrict by district
     */
    public function getSubDistricts($districtId)
    {
        $cacheKey = "rajaongkir_subdistricts_{$districtId}";

        return Cache::remember($cacheKey, 86400, function () use ($districtId) {
            try {
                $url = $this->baseUrl . '/destination/sub-district/' . urlencode($districtId);

                $response = Http::withHeaders([
                    'key' => $this->apiKey,
                    'accept' => 'application/json',
                ])->get($url);

                if ($response->json('meta.status') == 'success' && $response->json('meta.code') == 200) {
                    return $response->json('data');
                }

                return [];
            } catch (\Exception $e) {
                Log::error('RajaOngkir Get SubDistricts Error: ' . $e->getMessage());
                return [];
            }
        });
    }

    /**
     * Search city by keyword
     */
    public function searchCity($keyword)
    {
        $cities = $this->getCities();

        return array_filter($cities, function ($city) use ($keyword) {
            $cityName = strtolower($city['name'] ?? '');
            $searchTerm = strtolower($keyword);

            return strpos($cityName, $searchTerm) !== false;
        });
    }

    /**
     * Calculate shipping cost
     * 
     * @param int $originDistrictId Origin subdistrict ID
     * @param int $destinationDistrictId Destination subdistrict ID
     * @param int $weight Weight in grams
     * @param string $courier Courier code or 'all'
     */
    public function calculateCost($originDistrictId, $destinationDistrictId, $weight)
    {
        try {
            $response = Http::withHeaders([
                'key' => $this->apiKey,
                'accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ])->asForm()->post($this->baseUrl . '/calculate/district/domestic-cost', [
                'origin' => (string) $originDistrictId,
                'destination' => (string) $destinationDistrictId,
                'weight' => (int) $weight,
                'courier' => 'jne:sicepat:jnt:lion:pos',
                'price' => 'lowest',
            ]);

            Log::info('RajaOngkir Calculate Cost Request', [
                'origin' => $originDistrictId,
                'destination' => $destinationDistrictId,
                'weight' => $weight,
                'response' => $response->json()
            ]);

            if ($response->json('meta.status') == 'success' && $response->json('meta.code') == 200) {
                $results = $response->json('data');

                // Format response
                $formattedResults = [];
                if (isset($results) && is_array($results)) {
                    foreach ($results as $courierData) {
                        $formattedResults[] = [
                            'name' => $courierData['name'] ?? '',
                            'code' => $courierData['code'] ?? '',
                            'service' => $courierData['service'] ?? '',
                            'description' => $courierData['description'] ?? '',
                            'cost' => $courierData['cost'] ?? 0,
                            'etd' => $courierData['etd'] ?? '',
                        ];
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
                'message' => $response->json('meta.message') ?? 'Gagal menghitung ongkir',
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
     * Get Gorontalo Kota Subdistrict ID (untuk origin)
     * Sesuaikan dengan subdistrict ID lokasi toko Anda
     */
    public function getGorontaloOriginDistrictId()
    {
        return 2430; // Contoh ID, sesuaikan dengan data real Anda
    }

    public function getOriginCityId()
    {
        // Gorontalo City ID
        return 251;
    }
    /**
     * Check if destination is in same city as origin
     */
    public function isSameCity($originCityId, $destinationCityId)
    {
        // Jika subdistrict ID sama, pasti satu kota
        if ($originCityId == $destinationCityId) {
            return true;
        }

        return false;
    }
}