<?php

namespace App\Services;

use Carbon\Carbon;

class HijriCalendarService
{
    private const HIJRI_MONTHS = [
        1 => 'Muharram', 2 => 'Safar', 3 => 'Rabiul Awal',
        4 => 'Rabiul Akhir', 5 => 'Jumadil Awal', 6 => 'Jumadil Akhir',
        7 => 'Rajab', 8 => 'Sya\'ban', 9 => 'Ramadhan',
        10 => 'Syawal', 11 => 'Dzulqa\'dah', 12 => 'Dzulhijjah',
    ];

    public function getHijriMonths(): array
    {
        return self::HIJRI_MONTHS;
    }

    public function masehiToHijri(int $day, int $month, int $year): array
    {
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        $jd = $this->gregorianToJD($month, $day, $year);
        $hijri = $this->jdToIslamic($jd);

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
                'month_name' => self::HIJRI_MONTHS[$hijri['month']] ?? '',
                'year' => $hijri['year'],
                'formatted' => "{$hijri['day']} ".(self::HIJRI_MONTHS[$hijri['month']] ?? '')." {$hijri['year']} H",
            ],
        ];
    }

    public function hijriToMasehi(int $day, int $month, int $year): array
    {
        $jd = $this->islamicToJD($month, $day, $year);
        $gregorian = $this->jdToGregorian($jd);

        $timestamp = mktime(0, 0, 0, $gregorian['month'], $gregorian['day'], $gregorian['year']);

        return [
            'success' => true,
            'input' => [
                'type' => 'hijri',
                'day' => $day,
                'month' => $month,
                'month_name' => self::HIJRI_MONTHS[$month] ?? '',
                'year' => $year,
                'formatted' => "{$day} ".(self::HIJRI_MONTHS[$month] ?? '')." {$year} H",
            ],
            'result' => [
                'type' => 'masehi',
                'day' => $gregorian['day'],
                'month' => $gregorian['month'],
                'year' => $gregorian['year'],
                'formatted' => date('d/m/Y', $timestamp),
                'day_name' => Carbon::createFromTimestamp($timestamp)->locale('id')->dayName,
            ],
        ];
    }

    public function getMasehiMonthDays(int $month, int $year): array
    {
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $days = [];

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $jd = $this->gregorianToJD($month, $d, $year);
            $hijri = $this->jdToIslamic($jd);

            $days[] = [
                'day' => $d,
                'month' => $month,
                'year' => $year,
                'hijri_day' => $hijri['day'],
                'hijri_month' => $hijri['month'],
                'hijri_month_name' => self::HIJRI_MONTHS[$hijri['month']] ?? '',
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
            'month_name' => Carbon::create($year, $month, 1)->locale('id')->monthName,
            'days' => $days,
        ];
    }

    public function getHijriMonthDays(int $month, int $year): array
    {
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
                'hijri_month_name' => self::HIJRI_MONTHS[$month] ?? '',
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
            'month_name' => self::HIJRI_MONTHS[$month] ?? '',
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
        $l = floor($jd) - 1948440 + 10632;
        $n = floor(($l - 1) / 10631);
        $l = $l - 10631 * $n + 354;
        $j = floor((10985 - $l) / 5316) * floor((50 * $l) / 17719) + floor($l / 5670) * floor((43 * $l) / 15238);
        $l = $l - floor((30 - $j) / 15) * floor((17719 * $j) / 50) - floor($j / 16) * floor((15238 * $j) / 43) + 29;
        $month = floor((24 * $l) / 709);
        $day = $l - floor((709 * $month) / 24);
        $year = 30 * $n + $j - 30;

        return [
            'day' => (int) $day,
            'month' => (int) $month,
            'year' => (int) $year,
        ];
    }

    private function islamicToJD(int $month, int $day, int $year): float
    {
        return floor((11 * $year + 3) / 30) + 354 * $year + 30 * $month - floor(($month - 1) / 2) + $day + 1948440 - 385;
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
