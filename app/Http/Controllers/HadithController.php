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
        return view('hadith.index');
    }

    public function show(int $id)
    {
        $hadith = $this->hadithService->getHadith($id);

        return view('hadith.show', [
            'hadithId' => $id,
            'hadith' => $hadith,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        return view('hadith.search', [
            'query' => $query,
        ]);
    }
}
