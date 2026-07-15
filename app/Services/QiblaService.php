<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class QiblaService
{
    private MuslimApiService $api;

    public function __construct(MuslimApiService $api)
    {
        $this->api = $api;
    }

    public function getDirection(float $lat, float $lng): ?array
    {
        $cacheKey = "qibla:{$lat}:{$lng}";

        return Cache::remember($cacheKey, 86400, function () use ($lat, $lng) {
            $coordinate = sprintf('%s,%s', $lat, $lng);

            return $this->api->get("/qibla/{$coordinate}");
        });
    }

    public function getDistance(float $lat, float $lng): float
    {
        $kaabaLat = 21.4225;
        $kaabaLng = 39.8262;
        $earthRadius = 6371;

        $latFrom = deg2rad($lat);
        $lngFrom = deg2rad($lng);
        $latTo = deg2rad($kaabaLat);
        $lngTo = deg2rad($kaabaLng);

        $latDiff = $latTo - $latFrom;
        $lngDiff = $lngTo - $lngFrom;

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lngDiff / 2) * sin($lngDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}
