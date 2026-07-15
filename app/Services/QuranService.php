<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

class QuranService
{
    private MuslimApiService $api;

    private int $cacheTtl;

    public function __construct(MuslimApiService $api)
    {
        $this->api = $api;
        $this->cacheTtl = config('muslim.cache_ttl');
    }

    public function getSurahList(): Collection
    {
        $cacheKey = 'quran:surah_list';

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            $data = $this->api->get('/quran');

            return $data ? collect($data) : collect();
        });
    }

    public function getSurah(int $number): ?array
    {
        $cacheKey = "quran:surah:{$number}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($number) {
            $data = $this->api->get("/quran/{$number}");

            return $data;
        });
    }

    public function getAyah(int $surahNumber, int $ayahNumber): ?array
    {
        $cacheKey = "quran:ayah:{$surahNumber}:{$ayahNumber}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($surahNumber, $ayahNumber) {
            return $this->api->get("/quran/{$surahNumber}/{$ayahNumber}");
        });
    }

    public function getRandomAyah(): ?array
    {
        return $this->api->get('/quran/random');
    }

    public function search(string $query, int $limit = 10): ?array
    {
        return $this->api->post('/quran/search', [
            'keyword' => $query,
            'limit' => $limit,
        ]);
    }

    public function getAyahsByJuz(int $juz, int $page = 1, int $limit = 10): ?array
    {
        return $this->api->get("/quran/juz/{$juz}", [
            'page' => $page,
            'limit' => $limit,
        ]);
    }

    public function getAyahsByPage(int $page, int $limit = 10): ?array
    {
        return $this->api->get("/quran/page/{$page}", [
            'limit' => $limit,
        ]);
    }

    public function getSajdaAyahs(): ?array
    {
        return $this->api->get('/quran/sajda');
    }

    public function getAudioUrl(int $surahNumber): string
    {
        return "https://cdn.myquran.com/audio/surah/{$surahNumber}.mp3";
    }

    public function getAudioPageUrl(int $pageNumber): string
    {
        return "https://cdn.myquran.com/audio/page/{$pageNumber}.mp3";
    }
}
