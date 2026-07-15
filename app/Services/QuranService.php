<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class QuranService
{
    private string $apiUrl;

    private int $cacheTtl;

    public function __construct()
    {
        $this->apiUrl = config('quran.api_url');
        $this->cacheTtl = config('quran.cache_ttl');
    }

    public function getSurahList(): Collection
    {
        return Cache::remember('quran:surah_list', $this->cacheTtl, function () {
            $response = Http::get("{$this->apiUrl}/surah");

            if ($response->successful()) {
                return collect($response->json('data'));
            }

            return collect();
        });
    }

    public function getSurah(int $number): ?array
    {
        return Cache::remember("quran:surah:{$number}", $this->cacheTtl, function () use ($number) {
            $response = Http::get("{$this->apiUrl}/surah/{$number}");

            if ($response->successful()) {
                return $response->json('data');
            }

            return null;
        });
    }

    public function getSurahWithTranslation(int $surahNumber, int $translationId = 33): array
    {
        $cacheKey = "quran:surah:{$surahNumber}:translation:{$translationId}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($surahNumber, $translationId) {
            $response = Http::get("{$this->apiUrl}/surah/{$surahNumber}/editions", [
                'editions' => "quran-simple,$translationId",
            ]);

            if ($response->successful()) {
                $data = $response->json('data');

                return [
                    'arabic' => $data[0] ?? null,
                    'translation' => $data[1] ?? null,
                ];
            }

            return ['arabic' => null, 'translation' => null];
        });
    }

    public function getAyah(int $surahNumber, int $ayahNumber): ?array
    {
        $cacheKey = "quran:ayah:{$surahNumber}:{$ayahNumber}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($surahNumber, $ayahNumber) {
            $response = Http::get("{$this->apiUrl}/ayah/{$surahNumber}:{$ayahNumber}/editions", [
                'editions' => 'quran-simple,id',
            ]);

            if ($response->successful()) {
                $data = $response->json('data');

                return [
                    'arabic' => $data[0] ?? null,
                    'translation' => $data[1] ?? null,
                ];
            }

            return null;
        });
    }

    public function search(string $query): Collection
    {
        $cacheKey = 'quran:search:'.md5($query);

        return Cache::remember($cacheKey, 3600, function () use ($query) {
            $response = Http::get("{$this->apiUrl}/search", [
                'q' => $query,
                'language' => 'id',
            ]);

            if ($response->successful()) {
                return collect($response->json('data.matches'));
            }

            return collect();
        });
    }

    public function getAudioUrl(int $surahNumber, int $reciterId = 7): string
    {
        return "https://cdn.islamic.network/quran/audio-surah/128/{$reciterId}/{$surahNumber}.mp3";
    }

    public function getTafsir(int $surahNumber): ?array
    {
        $cacheKey = "quran:tafsir:{$surahNumber}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($surahNumber) {
            $response = Http::get("{$this->apiUrl}/surah/{$surahNumber}/editions", [
                'editions' => 'quran-simple,en-tafisr-ibn-kathir',
            ]);

            if ($response->successful()) {
                $data = $response->json('data');

                return [
                    'arabic' => $data[0] ?? null,
                    'tafsir' => $data[1] ?? null,
                ];
            }

            return null;
        });
    }
}
