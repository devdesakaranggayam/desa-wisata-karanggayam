<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RadiusController extends Controller
{
    public function getCenter()
    {
        $center = config_value('center_lat_lng');
        [$lat, $lng] = explode(',', $center);

        return response()->json([
            'center_raw' => $center,
            'lat' => (float) $lat,
            'lng' => (float) $lng,
        ]);
    }

    public function checkRadius(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        // Ambil center dari configs
        $center = config_value('center_lat_lng');
        [$centerLat, $centerLng] = explode(',', $center);

        // Ambil radius dari configs
        $radiusKm = (float) config_value('radius_km'); // misal: 10

        $userLat = (float) $request->lat;
        $userLng = (float) $request->lng;

        // Hitung jarak dengan Haversine Formula
        $distance = $this->haversine($centerLat, $centerLng, $userLat, $userLng);

        return response()->json([
            'distance_km' => $distance,
            'radius_km' => $radiusKm,
            'in_radius' => $distance <= $radiusKm,
        ]);
    }

    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta/2) * sin($lonDelta/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }
}
