<?php

namespace App\Http\Controllers;

use App\Services\MuslimApiService;
use Illuminate\Http\Request;

class HijriCalendarController extends Controller
{
    private MuslimApiService $muslimApi;

    public function __construct(MuslimApiService $muslimApi)
    {
        $this->muslimApi = $muslimApi;
    }

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
            $result = $this->masehiToHijri($day, $month, $year);
        } else {
            $result = $this->hijriToMasehi($day, $month, $year);
        }

        return response()->json($result);
    }

    private function masehiToHijri(int $day, int $month, int $year): array
    {
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        $jd = $this->gregorianToJD($month, $day, $year);
        $hijri = $this->jdToIslamic($jd);

        $hijriMonths = [
            1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal',
            4 => 'Rabiul Akhir', 5 => 'Jumadil Awal', 6 => 'Jumadil Akhir',
            7 => 'Rajab', 8 => 'Sya\'ban', 9 => 'Ramadhan',
            10 => 'Syawal', 11 => 'Dzulqa\'dah', 12 => 'Dzulhijjah'
        ];

        return [
            'success' => true,
            'input' => [
                'type' => 'masehi',
                'day' => $day,
                'month' => $month,
                'year' => $year,
                'formatted' => date('d/m/Y', $timestamp),
            ],
            'result' => [
                'type' => 'hijri',
                'day' => $hijri['day'],
                'month' => $hijri['month'],
                'month_name' => $hijriMonths[$hijri['month']] ?? '',
                'year' => $hijri['year'],
                'formatted' => "{$hijri['day']} {$hijriMonths[$hijri['month']]} {$hijri['year']} H",
            ],
        ];
    }

    private function hijriToMasehi(int $day, int $month, int $year): array
    {
        $jd = $this->islamicToJD($month, $day, $year);
        $gregorian = $this->jdToGregorian($jd);

        $timestamp = mktime(0, 0, 0, $gregorian['month'], $gregorian['day'], $gregorian['year']);

        $hijriMonths = [
            1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal',
            4 => 'Rabiul Akhir', 5 => 'Jumadil Awal', 6 => 'Jumadil Akhir',
            7 => 'Rajab', 8 => 'Sya\'ban', 9 => 'Ramadhan',
            10 => 'Syawal', 11 => 'Dzulqa\'dah', 12 => 'Dzulhijjah'
        ];

        return [
            'success' => true,
            'input' => [
                'type' => 'hijri',
                'day' => $day,
                'month' => $month,
                'month_name' => $hijriMonths[$month] ?? '',
                'year' => $year,
                'formatted' => "{$day} {$hijriMonths[$month]} {$year} H",
            ],
            'result' => [
                'type' => 'masehi',
                'day' => $gregorian['day'],
                'month' => $gregorian['month'],
                'year' => $gregorian['year'],
                'formatted' => date('d/m/Y', $timestamp),
                'day_name' => strftime('%A', $timestamp),
            ],
        ];
    }

    public function calendarMonth(Request $request)
    {
        $month = (int) $request->get('month', date('n'));
        $year = (int) $request->get('year', date('Y'));
        $type = $request->get('type', 'masehi');

        if ($type === 'hijri') {
            $days = $this->getHijriMonthDays($month, $year);
        } else {
            $days = $this->getMasehiMonthDays($month, $year);
        }

        return response()->json($days);
    }

    private function getMasehiMonthDays(int $month, int $year): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $days = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $jd = $this->gregorianToJD($month, $d, $year);
            $hijri = $this->jdToIslamic($jd);

            $hijriMonths = [
                1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal',
                4 => 'Rabiul Akhir', 5 => 'Jumadil Awal', 6 => 'Jumadil Akhir',
                7 => 'Rajab', 8 => 'Sya\'ban', 9 => 'Ramadhan',
                10 => 'Syawal', 11 => 'Dzulqa\'dah', 12 => 'Dzulhijjah'
            ];

            $days[] = [
                'day' => $d,
                'month' => $month,
                'year' => $year,
                'hijri_day' => $hijri['day'],
                'hijri_month' => $hijri['month'],
                'hijri_month_name' => $hijriMonths[$hijri['month']] ?? '',
                'hijri_year' => $hijri['year'],
                'is_today' => ($d == date('j') && $month == date('n') && $year == date('Y')),
                'is_ramadhan' => $hijri['month'] == 9,
                'is_friday' => date('w', mktime(0, 0, 0, $month, $d, $year)) == 5,
            ];
        }

        return [
            'success' => true,
            'type' => 'masehi',
            'month' => $month,
            'year' => $year,
            'month_name' => strftime('%B', mktime(0, 0, 0, $month, 1, $year)),
            'days' => $days,
        ];
    }

    private function getHijriMonthDays(int $month, int $year): array
    {
        $hijriMonths = [
            1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal',
            4 => 'Rabiul Akhir', 5 => 'Jumadil Awal', 6 => 'Jumadil Akhir',
            7 => 'Rajab', 8 => 'Sya\'ban', 9 => 'Ramadhan',
            10 => 'Syawal', 11 => 'Dzulqa\'dah', 12 => 'Dzulhijjah'
        ];

        $daysInMonth = $this->hijriDaysInMonth($month, $year);
        $days = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $jd = $this->islamicToJD($month, $d, $year);
            $gregorian = $this->jdToGregorian($jd);

            $days[] = [
                'day' => $d,
                'month' => $month,
                'year' => $year,
                'hijri_day' => $d,
                'hijri_month' => $month,
                'hijri_month_name' => $hijriMonths[$month] ?? '',
                'hijri_year' => $year,
                'gregorian_day' => $gregorian['day'],
                'gregorian_month' => $gregorian['month'],
                'gregorian_year' => $gregorian['year'],
                'is_today' => ($gregorian['day'] == date('j') && $gregorian['month'] == date('n') && $gregorian['year'] == date('Y')),
                'is_ramadhan' => $month == 9,
                'is_friday' => date('w', mktime(0, 0, 0, $gregorian['month'], $gregorian['day'], $gregorian['year'])) == 5,
            ];
        }

        return [
            'success' => true,
            'type' => 'hijri',
            'month' => $month,
            'year' => $year,
            'month_name' => $hijriMonths[$month] ?? '',
            'days' => $days,
        ];
    }

    private function hijriDaysInMonth(int $month, int $year): int
    {
        $jd = $this->islamicToJD($month, 30, $year);
        $gregorian = $this->jdToGregorian($jd);
        $jd2 = $this->gregorianToJD($gregorian['month'], $gregorian['day'], $gregorian['year']);
        $hijri = $this->jdToIslamic($jd2);

        if ($hijri['day'] == 30) {
            return 30;
        }
        return 29;
    }

    private function gregorianToJD(int $month, int $day, int $year): float
    {
        if ($month <= 2) {
            $year -= 1;
            $month += 12;
        }
        $A = floor($year / 100);
        $B = 2 - $A + floor($A / 4);
        return floor(365.25 * ($year + 4716)) + floor(30.6001 * ($month + 1)) + $day + $B - 1524.5;
    }

    private function jdToIslamic(float $jd): array
    {
        $jd = floor($jd) + 0.5;
        $y = 10631.0 / 30.0;
        $epoch = 1948439.5;
        $shift = 8.01 / 60.0;

        $z = $jd - $epoch + $shift;
        $cycles = floor($z / 10631.0);
        $z -= 10631.0 * $cycles;
        $z -= 0.5;
        $j = floor(($z - 1) / $y);
        $z -= $j * $y;
        $day = $z + 1;
        $month = floor(($j + 2) / 30.0) + 1;
        $year = 30 * $cycles + $j;

        if ($month > 12) {
            $month -= 12;
            $year += 1;
        }

        return [
            'day' => (int) $day,
            'month' => (int) $month,
            'year' => (int) $year,
        ];
    }

    private function islamicToJD(int $month, int $day, int $year): float
    {
        $y = 10631.0 / 30.0;
        $epoch = 1948439.5;
        $shift = 8.01 / 60.0;

        $cycle = floor($year / 30);
        $cyear = $year - 30 * $cycle;
        $jd = floor($y * $cyear) + floor(($month - 1) * 30.0) + $day + $epoch - $shift;
        $jd += 10631.0 * $cycle;

        return $jd - 0.5;
    }

    private function jdToGregorian(float $jd): array
    {
        $jd += 0.5;
        $Z = floor($jd);
        $F = $jd - $Z;
        $A = $Z;
        if ($Z >= 2299161) {
            $alpha = floor(($Z - 1867216.25) / 36524.25);
            $A = $Z + 1 + $alpha - floor($alpha / 4);
        }
        $B = $A + 1524;
        $C = floor(($B - 122.1) / 365.25);
        $D = floor(365.25 * $C);
        $E = floor(($B - $D) / 30.6001);

        $day = $B - $D - floor(30.6001 * $E);
        $month = ($E < 14) ? $E - 1 : $E - 13;
        $year = ($month > 2) ? $C - 4716 : $C - 4715;

        return [
            'day' => (int) $day,
            'month' => (int) $month,
            'year' => (int) $year,
        ];
    }
}
