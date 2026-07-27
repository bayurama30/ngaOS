<?php

namespace App\Http\Controllers;

class PrayerTimeController extends Controller
{
    public function index()
    {
        return view('prayer.index');
    }
}
