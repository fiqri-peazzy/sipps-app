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
     * Get districts by city
     */
    public function getDistricts(Request $request)
    {
        $cityId = $request->get('city_id');

        if (!$cityId) {
            return response()->json([
                'success' => false,
                'message' => 'City ID is required',
                'data' => []
            ], 400);
        }

        $districts = $this->rajaOngkir->getDistricts($cityId);

        return response()->json([
            'success' => true,
            'data' => $districts
        ]);
    }

    /**
     * Get subdistricts by district
     */
    public function getSubDistricts(Request $request)
    {
        $districtId = $request->get('district_id');

        if (!$districtId) {
            return response()->json([
                'success' => false,
                'message' => 'District ID is required',
                'data' => []
            ], 400);
        }

        $subdistricts = $this->rajaOngkir->getSubDistricts($districtId);

        return response()->json([
            'success' => true,
            'data' => $subdistricts
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
            // 'destination_subdistrict_id' => 'required|integer',
            'destination_city_id' => 'required|integer',
            'destination_district_id' => 'required|integer',
            'weight' => 'required|integer|min:1',
        ]);

        $originCity = $this->rajaOngkir->getOriginCityId();
        $destinationCity = $request->destination_city_id;
        $weight = $request->weight;

        // Check if same city (dalam kota)
        if ($this->rajaOngkir->isSameCity($originCity, $destinationCity)) {
            return response()->json([
                'success' => true,
                'is_same_city' => true,
                'data' => [
                    [
                        'name' => 'Pengiriman Dalam Kota',
                        'code' => 'local',
                        'service' => 'FLAT',
                        'description' => 'Pengiriman Dalam Kota/Kabupaten Gorontalo',
                        'cost' => 6000,
                        'etd' => '1 hari',
                    ]
                ]
            ]);
        }

        $originDistrictId = $this->rajaOngkir->getGorontaloOriginDistrictId();
        $destinationDistrictId = $request->destination_district_id;

        // Calculate using RajaOngkir for antar kota
        $result = $this->rajaOngkir->calculateCost(
            $originDistrictId,
            $destinationDistrictId,
            $weight
        );

        return response()->json($result);
    }
}
