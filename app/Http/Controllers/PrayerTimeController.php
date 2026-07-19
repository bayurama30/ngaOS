<?php

namespace App\Http\Controllers;

use App\Services\PrayerTimeService;

class PrayerTimeController extends Controller
{
    public function __construct(
        private PrayerTimeService $prayerService
    ) {}

    public function index()
    {
        return view('prayer.index');
    }
}
