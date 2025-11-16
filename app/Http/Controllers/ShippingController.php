<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Get list provinces
     */
    public function getProvinces()
    {
        $provinces = $this->rajaOngkir->getProvinces();

        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Get cities by province
     */
    public function getCities(Request $request)
    {
        $provinceId = $request->get('province_id');
        $cities = $this->rajaOngkir->getCities($provinceId);

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    /**
     * Search city by keyword
     */
    public function searchCity(Request $request)
    {
        $keyword = $request->get('q', '');

        if (strlen($keyword) < 3) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal 3 karakter untuk pencarian',
                'data' => []
            ]);
        }

        $cities = $this->rajaOngkir->searchCity($keyword);

        return response()->json([
            'success' => true,
            'data' => array_values($cities)
        ]);
    }

    /**
     * Calculate shipping cost
     */
    public function calculateCost(Request $request)
    {
        $request->validate([
            'destination_city_id' => 'required|integer',
            'weight' => 'required|integer|min:1',
        ]);

        $originCityId = $this->rajaOngkir->getGorontaloCityId();

        if (!$originCityId) {
            return response()->json([
                'success' => false,
                'message' => 'Origin city (Gorontalo) not found'
            ], 500);
        }

        $destinationCityId = $request->destination_city_id;
        $weight = $request->weight;

        // Check if same city (dalam kota)
        if ($originCityId == $destinationCityId) {
            return response()->json([
                'success' => true,
                'is_same_city' => true,
                'data' => [
                    [
                        'name' => 'Pengiriman Dalam Kota',
                        'code' => 'local',
                        'service' => 'FLAT',
                        'description' => 'Pengiriman Dalam Kota Gorontalo',
                        'cost' => 10000,
                        'etd' => '1-2 hari',
                    ]
                ]
            ]);
        }

        // Calculate using RajaOngkir for antar kota
        $result = $this->rajaOngkir->calculateCost(
            $originCityId,
            $destinationCityId,
            $weight,
            'all' // Get all couriers
        );

        return response()->json($result);
    }
}
