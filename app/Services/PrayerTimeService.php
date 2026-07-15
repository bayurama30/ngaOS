<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class PrayerTimeService
{
    private MuslimApiService $api;

    private int $cacheTtl;

    public function __construct(MuslimApiService $api)
    {
        $this->api = $api;
        $this->cacheTtl = config('muslim.cache_ttl');
    }

    public function searchCity(string $keyword): ?array
    {
        $cacheKey = 'muslim:city:search:' . md5($keyword);

        return Cache::remember($cacheKey, 86400, function () use ($keyword) {
            return $this->api->get("/sholat/kabkota/cari/{$keyword}");
        });
    }

    public function getCityById(string $id): ?array
    {
        $cacheKey = "muslim:city:{$id}";

        return Cache::remember($cacheKey, 86400, function () use ($id) {
            return $this->api->get("/sholat/kabkota/{$id}");
        });
    }

    public function getTodaySchedule(string $cityId, string $timezone = 'Asia/Jakarta'): ?array
    {
        $cacheKey = "muslim:prayer:today:{$cityId}:{$timezone}";

        return Cache::remember($cacheKey, 3600, function () use ($cityId, $timezone) {
            return $this->api->get("/sholat/jadwal/{$cityId}/today", [
                'tz' => $timezone,
            ]);
        });
    }

    public function getScheduleByDate(string $cityId, string $date, string $timezone = 'Asia/Jakarta'): ?array
    {
        $cacheKey = "muslim:prayer:{$cityId}:{$date}:{$timezone}";

        return Cache::remember($cacheKey, 3600, function () use ($cityId, $date, $timezone) {
            return $this->api->get("/sholat/jadwal/{$cityId}/{$date}", [
                'tz' => $timezone,
            ]);
        });
    }

    public function getMonthlySchedule(string $cityId, int $month, int $year, string $timezone = 'Asia/Jakarta'): ?array
    {
        $period = sprintf('%04d-%02d', $year, $month);
        $cacheKey = "muslim:prayer:monthly:{$cityId}:{$period}:{$timezone}";

        return Cache::remember($cacheKey, 86400, function () use ($cityId, $period, $timezone) {
            return $this->api->get("/sholat/jadwal/{$cityId}/{$period}", [
                'tz' => $timezone,
            ]);
        });
    }

    public function getNextPrayer(?array $schedule): ?array
    {
        if (!$schedule || !isset($schedule['jadwal'])) {
            return null;
        }

        $jadwal = $schedule['jadwal'];
        $now = new \DateTime();
        $today = $now->format('Y-m-d');

        $prayers = [
            'Subuh' => $jadwal['subuh'] ?? null,
            'Terbit' => $jadwal['terbit'] ?? null,
            'Dhuha' => $jadwal['dhuha'] ?? null,
            'Dzuhur' => $jadwal['dzuhur'] ?? null,
            'Ashar' => $jadwal['ashar'] ?? null,
            'Maghrib' => $jadwal['maghrib'] ?? null,
            'Isya' => $jadwal['isya'] ?? null,
        ];

        foreach ($prayers as $name => $time) {
            if (!$time) continue;

            $prayerTime = \DateTime::createFromFormat('H:i', $time);

            if ($prayerTime > $now) {
                $diff = $now->diff($prayerTime);

                return [
                    'name' => $name,
                    'time' => $time,
                    'remaining' => $diff->format('%h jam %i menit'),
                ];
            }
        }

        $firstPrayer = \DateTime::createFromFormat('H:i', $prayers['Subuh']);
        $firstPrayer->modify('+1 day');
        $diff = $now->diff($firstPrayer);

        return [
            'name' => 'Subuh',
            'time' => $prayers['Subuh'],
            'remaining' => $diff->format('%h jam %i menit'),
        ];
    }
}
