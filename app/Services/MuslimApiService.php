<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MuslimApiService
{
    private string $apiUrl;

    private int $cacheTtl;

    public function __construct()
    {
        $this->apiUrl = config('muslim.api_url');
        $this->cacheTtl = config('muslim.cache_ttl');
    }

    public function get(string $endpoint, array $query = []): ?array
    {
        // Variant-randomization params (`t=Date.now()`, `_=...`) break file
        // caching because each request builds a unique cache key. Strip them
        // from the cache key only (the real upstream URL still gets them).
        $cacheQuery = array_filter($query, fn ($k) => ! in_array($k, ['t', '_', 'ts'], true), ARRAY_FILTER_USE_KEY);
        $cacheKey = 'muslim:'.$endpoint.'?'.http_build_query($cacheQuery);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($endpoint, $query) {
            try {
                $response = Http::timeout(15)->get("{$this->apiUrl}{$endpoint}", $query);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['status']) && $data['status'] === true) {
                        return $data['data'] ?? $data;
                    }

                    return $data;
                }

                Log::error('Muslim API Error', [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                ]);

                return null;
            } catch (\Exception $e) {
                Log::error('Muslim API Exception', [
                    'endpoint' => $endpoint,
                    'message' => $e->getMessage(),
                ]);

                return null;
            }
        });
    }

    public function post(string $endpoint, array $body = []): ?array
    {
        try {
            $response = Http::timeout(15)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}{$endpoint}", $body);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === true) {
                    return $data['data'] ?? $data;
                }

                return $data;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Muslim API POST Exception', [
                'endpoint' => $endpoint,
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    public function clearCache(?string $endpoint = null): void
    {
        if ($endpoint) {
            Cache::forget('muslim:'.$endpoint.'?');
        }
    }
}
