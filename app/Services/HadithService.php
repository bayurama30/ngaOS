<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class HadithService
{
    private MuslimApiService $api;

    private int $cacheTtl;

    public function __construct(MuslimApiService $api)
    {
        $this->api = $api;
        $this->cacheTtl = config('muslim.cache_ttl');
    }

    public function getMukharrijList(): array
    {
        return [
            'muttafaq-alaih' => [
                'name' => 'Muttafaq \'alaih',
                'description' => 'Hadis yang disepakati Imam Bukhari dan Imam Muslim',
                'color' => 'emerald',
                'icon' => '🤝',
                'scholars' => 'Imam Bukhari & Imam Muslim',
                'keywords' => ['Muttafaq', 'Muttafaq \'alaih', 'Muttafaq \'alaihi'],
            ],
            'bukhari' => [
                'name' => 'Sahih Bukhari',
                'description' => 'Kumpulan hadis sahih karya Imam Bukhari',
                'color' => 'blue',
                'icon' => '📗',
                'scholar' => 'Imam Abu Abdullah al-Bukhari',
                'death' => '256 H',
                'keywords' => ['Bukhari', 'HR. Bukhari'],
            ],
            'muslim' => [
                'name' => 'Sahih Muslim',
                'description' => 'Kumpulan hadis sahih karya Imam Muslim',
                'color' => 'purple',
                'icon' => '📘',
                'scholar' => 'Imam Muslim bin al-Hajjaj',
                'death' => '261 H',
                'keywords' => ['Muslim'],
            ],
            'tirmidzi' => [
                'name' => 'Jami\' at-Tirmidzi',
                'description' => 'Kumpulan hadis karya Imam Tirmidzi',
                'color' => 'amber',
                'icon' => '📙',
                'scholar' => 'Imam Abu Isa at-Tirmidzi',
                'death' => '279 H',
                'keywords' => ['Tirmidzi', 'Tirmiżi', 'Tirmizi'],
            ],
            'abu-daud' => [
                'name' => 'Sunan Abu Daud',
                'description' => 'Kumpulan hadis karya Imam Abu Daud',
                'color' => 'cyan',
                'icon' => '📗',
                'scholar' => 'Imam Abu Daud as-Sijistani',
                'death' => '275 H',
                'keywords' => ['Abu Daud'],
            ],
            'ahmad' => [
                'name' => 'Musnad Ahmad',
                'description' => 'Kumpulan hadis terbesar karya Imam Ahmad',
                'color' => 'red',
                'icon' => '📕',
                'scholar' => 'Imam Ahmad bin Hanbal',
                'death' => '241 H',
                'keywords' => ['Ahmad'],
            ],
        ];
    }

    public function getMukharrij(string $key): ?array
    {
        $list = $this->getMukharrijList();
        return $list[$key] ?? null;
    }

    public function getHadithsByMukharrij(string $mukharrijKey, int $page = 1, int $limit = 10): array
    {
        $mukharrij = $this->getMukharrij($mukharrijKey);

        if (!$mukharrij) {
            return ['hadis' => [], 'paging' => []];
        }

        $cacheKey = "hadith:mukharrij:{$mukharrijKey}:{$page}:{$limit}";

        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        $allHadis = [];
        $keywords = $mukharrij['keywords'];

        for ($p = 1; $p <= 50; $p++) {
            $response = $this->api->get('/hadis/enc/explore', [
                'page' => $p,
                'limit' => 10,
            ]);

            if (!$response || !isset($response['hadis'])) {
                break;
            }

            foreach ($response['hadis'] as $hadis) {
                $takhrij = $hadis['takhrij'] ?? '';

                foreach ($keywords as $keyword) {
                    if (stripos($takhrij, $keyword) !== false) {
                        $allHadis[] = $hadis;
                        break;
                    }
                }
            }

            if (!$response['paging']['has_next']) {
                break;
            }
        }

        $total = count($allHadis);
        $offset = ($page - 1) * $limit;
        $paginatedHadis = array_slice($allHadis, $offset, $limit);

        $result = [
            'hadis' => $paginatedHadis,
            'paging' => [
                'current' => $page,
                'per_page' => $limit,
                'total_data' => $total,
                'total_pages' => ceil($total / $limit),
                'has_prev' => $page > 1,
                'has_next' => $page < ceil($total / $limit),
            ],
        ];

        Cache::put($cacheKey, $result, $this->cacheTtl);

        return $result;
    }

    public function getMukharrijCounts(): array
    {
        $cacheKey = 'hadith:mukharrij:counts';

        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        $counts = [];
        $mukharrijList = $this->getMukharrijList();

        foreach ($mukharrijList as $key => $mukharrij) {
            $counts[$key] = 0;
        }

        for ($p = 1; $p <= 50; $p++) {
            $response = $this->api->get('/hadis/enc/explore', [
                'page' => $p,
                'limit' => 10,
            ]);

            if (!$response || !isset($response['hadis'])) {
                break;
            }

            foreach ($response['hadis'] as $hadis) {
                $takhrij = $hadis['takhrij'] ?? '';

                foreach ($mukharrijList as $key => $mukharrij) {
                    foreach ($mukharrij['keywords'] as $keyword) {
                        if (stripos($takhrij, $keyword) !== false) {
                            $counts[$key]++;
                            break;
                        }
                    }
                }
            }

            if (!$response['paging']['has_next']) {
                break;
            }
        }

        Cache::put($cacheKey, $counts, $this->cacheTtl * 7);

        return $counts;
    }

    public function getHadith(int $id): ?array
    {
        $cacheKey = "hadith:enc:{$id}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($id) {
            return $this->api->get("/hadis/enc/show/{$id}");
        });
    }

    public function getRandomHadith(): ?array
    {
        return $this->api->get('/hadis/enc/random');
    }

    public function getNextHadith(int $id): ?array
    {
        return $this->api->get("/hadis/enc/next/{$id}");
    }

    public function getPrevHadith(int $id): ?array
    {
        return $this->api->get("/hadis/enc/prev/{$id}");
    }

    public function explore(int $page = 1, int $limit = 5): ?array
    {
        return $this->api->get('/hadis/enc/explore', [
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function search(string $keyword, int $page = 1, int $limit = 10): ?array
    {
        return $this->api->get("/hadis/enc/cari/{$keyword}", [
            'page' => $page,
            'limit' => $limit,
        ]);
    }
}
