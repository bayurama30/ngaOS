<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class QiblaService
{
    private float $kaabaLat;

    private float $kaabaLng;

    public function __construct()
    {
        $this->kaabaLat = config('prayer.qibla_kaaba.lat');
        $this->kaabaLng = config('prayer.qibla_kaaba.lng');
    }

    public function getDirection(float $lat, float $lng): float
    {
        $cacheKey = "qibla:{$lat}:{$lng}";

        return Cache::remember($cacheKey, 86400, function () use ($lat, $lng) {
            $response = Http::get("https://api.aladhan.com/v1/qibla/{$lat}/{$lng}");

            if ($response->successful()) {
                return (float) $response->json('data.direction');
            }

            return $this->calculateDirection($lat, $lng);
        });
    }

    private function calculateDirection(float $lat, float $lng): float
    {
        $latFrom = deg2rad($lat);
        $lngFrom = deg2rad($lng);
        $latTo = deg2rad($this->kaabaLat);
        $lngTo = deg2rad($this->kaabaLng);

        $lngDiff = $lngTo - $lngFrom;

        $y = sin($lngDiff) * cos($latTo);
        $x = cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lngDiff);

        $bearing = atan2($y, $x);
        $bearing = rad2deg($bearing);
        $bearing = fmod($bearing + 360, 360);

        return round($bearing, 2);
    }

    public function getDistance(float $lat, float $lng): float
    {
        $earthRadius = 6371;

        $latFrom = deg2rad($lat);
        $lngFrom = deg2rad($lng);
        $latTo = deg2rad($this->kaabaLat);
        $lngTo = deg2rad($this->kaabaLng);

        $latDiff = $latTo - $latFrom;
        $lngDiff = $lngTo - $lngFrom;

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lngDiff / 2) * sin($lngDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
}
