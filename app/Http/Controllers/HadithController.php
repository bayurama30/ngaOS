<?php

namespace App\Http\Controllers;

use App\Services\HadithService;
use Illuminate\Http\Request;

class HadithController extends Controller
{
    public function __construct(
        private HadithService $hadithService
    ) {}

    public function index()
    {
        $books = $this->hadithService->getBooks();

        return view('hadith.index', [
            'books' => $books,
        ]);
    }

    public function book(string $slug)
    {
        return view('hadith.book', [
            'bookSlug' => $slug,
        ]);
    }

    public function hadith(string $slug, int $number)
    {
        $hadith = $this->hadithService->getHadith($slug, $number);

        return view('hadith.show', [
            'bookSlug' => $slug,
            'hadithNumber' => $number,
            'hadith' => $hadith,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $book = $request->get('book');

        if (strlen($query) < 3) {
            return view('hadith.search', [
                'query' => $query,
                'results' => collect(),
            ]);
        }

        $results = $this->hadithService->search($query, $book);

        return view('hadith.search', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
