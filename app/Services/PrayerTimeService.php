<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PrayerTimeService
{
    private string $apiUrl;

    private int $cacheTtl;

    public function __construct()
    {
        $this->apiUrl = config('prayer.api_url');
        $this->cacheTtl = config('prayer.cache_ttl');
    }

    public function getTimings(float $lat, float $lng, ?string $date = null, int $method = 2): ?array
    {
        $date = $date ?? Carbon::now()->format('d-m-Y');
        $cacheKey = "prayer:timings:{$lat}:{$lng}:{$date}:{$method}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($lat, $lng, $date, $method) {
            $timestamp = Carbon::createFromFormat('d-m-Y', $date)->timestamp;

            $response = Http::get("{$this->apiUrl}/timings/{$timestamp}", [
                'latitude' => $lat,
                'longitude' => $lng,
                'method' => $method,
            ]);

            if ($response->successful()) {
                return $response->json('data');
            }

            return null;
        });
    }

    public function getMonthlyCalendar(float $lat, float $lng, ?int $month = null, ?int $year = null, int $method = 2): array
    {
        $month = $month ?? Carbon::now()->month;
        $year = $year ?? Carbon::now()->year;
        $cacheKey = "prayer:calendar:{$lat}:{$lng}:{$month}:{$year}:{$method}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($lat, $lng, $month, $year, $method) {
            $response = Http::get("{$this->apiUrl}/calendar/{$year}/{$month}", [
                'latitude' => $lat,
                'longitude' => $lng,
                'method' => $method,
            ]);

            if ($response->successful()) {
                return $response->json('data');
            }

            return [];
        });
    }

    public function getNextPrayer(float $lat, float $lng, int $method = 2): ?array
    {
        $timings = $this->getTimings($lat, $lng, null, $method);

        if (! $timings || ! isset($timings['timings'])) {
            return null;
        }

        $timezone = $timings['meta']['timezone'] ?? 'UTC';

        try {
            $now = Carbon::now($timezone);
        } catch (\Exception $e) {
            $now = Carbon::now();
        }

        $prayers = [
            'Fajr' => $timings['timings']['Fajr'] ?? null,
            'Sunrise' => $timings['timings']['Sunrise'] ?? null,
            'Dhuhr' => $timings['timings']['Dhuhr'] ?? null,
            'Asr' => $timings['timings']['Asr'] ?? null,
            'Maghrib' => $timings['timings']['Maghrib'] ?? null,
            'Isha' => $timings['timings']['Isha'] ?? null,
        ];

        foreach ($prayers as $name => $time) {
            if (! $time) {
                continue;
            }

            $prayerTime = Carbon::createFromFormat('H:i', substr($time, 0, 5), $timezone);

            if ($prayerTime->gt($now)) {
                return [
                    'name' => $name,
                    'time' => substr($time, 0, 5),
                    'remaining' => $now->diff($prayerTime)->format('%h jam %i menit'),
                    'timestamp' => $prayerTime->timestamp,
                    'timezone' => $timezone,
                ];
            }
        }

        $firstPrayer = Carbon::createFromFormat('H:i', substr($prayers['Fajr'], 0, 5), $timezone)->addDay();

        return [
            'name' => 'Fajr',
            'time' => substr($prayers['Fajr'], 0, 5),
            'remaining' => $now->diff($firstPrayer)->format('%h jam %i menit'),
            'timestamp' => $firstPrayer->timestamp,
            'timezone' => $timezone,
        ];
    }

    public function getTimezone(float $lat, float $lng): string
    {
        $timings = $this->getTimings($lat, $lng);

        return $timings['meta']['timezone'] ?? 'Asia/Jakarta';
    }

    public function getCalculationMethods(): array
    {
        return config('prayer.calculation_methods');
    }
}
