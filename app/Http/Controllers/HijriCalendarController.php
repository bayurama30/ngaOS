<?php

namespace App\Http\Controllers;

use App\Services\HijriCalendarService;
use App\Services\MuslimApiService;
use Illuminate\Http\Request;

class HijriCalendarController extends Controller
{
    public function __construct(
        private MuslimApiService $muslimApi,
        private HijriCalendarService $hijriService,
    ) {}

    public function index()
    {
        return view('hijri.index');
    }

    public function today(Request $request)
    {
        $timezone = $request->get('tz', config('muslim.default_timezone'));

        $data = $this->muslimApi->get('/cal/today', [
            'tz' => $timezone,
        ]);

        return response()->json($data);
    }

    public function holidays()
    {
        $holidays = [
            [
                'name' => 'Tahun Baru Islam',
                'hijri' => '1 Muharram',
                'description' => 'Tahun baru kalender Hijriah',
                'icon' => '🌙',
                'month' => 1,
                'day' => 1,
            ],
            [
                'name' => 'Asyura',
                'hijri' => '10 Muharram',
                'description' => 'Hari puasa sunnah Asyura',
                'icon' => '🕌',
                'month' => 1,
                'day' => 10,
            ],
            [
                'name' => 'Isra Mi\'raj',
                'hijri' => '27 Rajab',
                'description' => 'Perjalanan Nabi Muhammad SAW dari Makkah ke Masjidil Aqsadan ke Sidratul Muntaha',
                'icon' => '🕌',
                'month' => 7,
                'day' => 27,
            ],
            [
                'name' => 'Nisfu Sya\'ban',
                'hijri' => '15 Sya\'ban',
                'description' => 'Malam pertengahan bulan Sya\'ban',
                'icon' => '🌙',
                'month' => 8,
                'day' => 15,
            ],
            [
                'name' => 'Awal Ramadhan',
                'hijri' => '1 Ramadhan',
                'description' => 'Awal bulan puasa Ramadhan',
                'icon' => '🌙',
                'month' => 9,
                'day' => 1,
            ],
            [
                'name' => 'Nuzulul Quran',
                'hijri' => '17 Ramadhan',
                'description' => 'Hari diturunkannya Al-Quran',
                'icon' => '📖',
                'month' => 9,
                'day' => 17,
            ],
            [
                'name' => 'Lailatul Qadr',
                'hijri' => '27 Ramadhan',
                'description' => 'Malam yang lebih baik dari 1000 bulan',
                'icon' => '✨',
                'month' => 9,
                'day' => 27,
            ],
            [
                'name' => 'Idul Fitri',
                'hijri' => '1 Syawal',
                'description' => 'Hari Raya Idul Fitri',
                'icon' => '🎉',
                'month' => 10,
                'day' => 1,
            ],
            [
                'name' => 'Hari Arafah',
                'hijri' => '9 Dzulhijjah',
                'description' => 'Hari wukuf di Arafah',
                'icon' => '🕋',
                'month' => 12,
                'day' => 9,
            ],
            [
                'name' => 'Idul Adha',
                'hijri' => '10 Dzulhijjah',
                'description' => 'Hari Raya Idul Adha / Kurban',
                'icon' => '🕋',
                'month' => 12,
                'day' => 10,
            ],
        ];

        return response()->json($holidays);
    }

    public function convert(Request $request)
    {
        $type = $request->get('type', 'masehi-to-hijri');
        $day = (int) $request->get('day', 1);
        $month = (int) $request->get('month', 1);
        $year = (int) $request->get('year', 2026);

        if ($type === 'masehi-to-hijri') {
            $result = $this->hijriService->masehiToHijri($day, $month, $year);
        } else {
            $result = $this->hijriService->hijriToMasehi($day, $month, $year);
        }

        return response()->json($result);
    }

    public function calendarMonth(Request $request)
    {
        $month = (int) $request->get('month', date('n'));
        $year = (int) $request->get('year', date('Y'));
        $type = $request->get('type', 'masehi');

        if ($type === 'hijri') {
            $days = $this->hijriService->getHijriMonthDays($month, $year);
        } else {
            $days = $this->hijriService->getMasehiMonthDays($month, $year);
        }

        return response()->json($days);
    }
}
