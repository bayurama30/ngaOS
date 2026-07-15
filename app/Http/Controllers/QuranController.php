<?php

namespace App\Http\Controllers;

use App\Services\QuranService;
use Illuminate\Http\Request;

class QuranController extends Controller
{
    public function __construct(
        private QuranService $quranService
    ) {}

    public function index()
    {
        return view('quran.index');
    }

    public function surah(int $number)
    {
        return view('quran.surah', [
            'surahNumber' => $number,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 3) {
            return view('quran.search', [
                'query' => $query,
                'results' => collect(),
            ]);
        }

        $results = $this->quranService->search($query);

        return view('quran.search', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
