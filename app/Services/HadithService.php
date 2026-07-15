<?php

namespace App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HadithService
{
    private string $apiUrl;

    private int $cacheTtl;

    public function __construct()
    {
        $this->apiUrl = config('hadith.api_url');
        $this->cacheTtl = config('hadith.cache_ttl');
    }

    public function getBooks(): array
    {
        return config('hadith.books');
    }

    public function getBook(string $slug): ?array
    {
        $books = $this->getBooks();

        return $books[$slug] ?? null;
    }

    public function getHadiths(string $bookSlug, int $page = 1, int $perPage = 20): LengthAwarePaginator
    {
        $book = $this->getBook($bookSlug);

        if (! $book) {
            return new LengthAwarePaginator(collect(), 0, $perPage);
        }

        $cacheKey = "hadith:{$bookSlug}:page:{$page}";

        $data = Cache::remember($cacheKey, $this->cacheTtl, function () use ($bookSlug) {
            $response = Http::get("{$this->apiUrl}/editions/{$bookSlug}.json");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        });

        if (! $data || ! isset($data['hadiths'])) {
            return new LengthAwarePaginator(collect(), 0, $perPage);
        }

        $hadiths = collect($data['hadiths']);
        $total = $hadiths->count();
        $items = $hadiths->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }

    public function getHadith(string $bookSlug, int $hadithNumber): ?array
    {
        $cacheKey = "hadith:{$bookSlug}:{$hadithNumber}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($bookSlug, $hadithNumber) {
            $response = Http::get("{$this->apiUrl}/editions/{$bookSlug}/hadiths/{$hadithNumber}.json");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        });
    }

    public function search(string $query, ?string $bookSlug = null): Collection
    {
        $cacheKey = 'hadith:search:'.md5($query.$bookSlug);

        return Cache::remember($cacheKey, 3600, function () use ($query, $bookSlug) {
            $results = collect();

            $books = $bookSlug ? [$bookSlug => $this->getBook($bookSlug)] : $this->getBooks();

            foreach ($books as $slug => $book) {
                if (! $book) {
                    continue;
                }

                $response = Http::get("{$this->apiUrl}/editions/{$slug}.json");

                if ($response->successful()) {
                    $hadiths = $response->json('hadiths', []);

                    foreach ($hadiths as $hadith) {
                        $text = $hadith['body'] ?? '';
                        if (stripos($text, $query) !== false) {
                            $results->push([
                                'book' => $book['name'],
                                'book_slug' => $slug,
                                'hadith' => $hadith,
                            ]);
                        }
                    }
                }

                if ($results->count() >= 50) {
                    break;
                }
            }

            return $results;
        });
    }

    public function getBookInfo(string $bookSlug): ?array
    {
        $cacheKey = "hadith:info:{$bookSlug}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($bookSlug) {
            $response = Http::get("{$this->apiUrl}/editions/{$bookSlug}.json");

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'book' => $data['metadata'] ?? [],
                    'total' => $data['metadata']['hadiths_count_number'] ?? 0,
                ];
            }

            return null;
        });
    }
}
