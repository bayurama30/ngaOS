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

    public function getEncyclopediaMeta(): ?array
    {
        $cacheKey = 'hadith:enc:meta';

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            return $this->api->get('/hadis/enc');
        });
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

    public function getNarratorsSummary(): ?array
    {
        $cacheKey = 'hadith:perawi:summary';

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            return $this->api->get('/hadis/perawi');
        });
    }

    public function getNarratorDetail(int $id): ?array
    {
        $cacheKey = "hadith:perawi:{$id}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($id) {
            return $this->api->get("/hadist/perawi/id/{$id}");
        });
    }

    public function browseNarrators(int $page = 1, int $limit = 10): ?array
    {
        return $this->api->get('/hadist/perawi/browse', [
            'page' => $page,
            'limit' => $limit,
        ]);
    }
}
