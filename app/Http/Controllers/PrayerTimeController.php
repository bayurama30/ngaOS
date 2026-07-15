<?php

namespace App\Http\Controllers;

use App\Services\PrayerTimeService;
use Illuminate\Http\Request;

class PrayerTimeController extends Controller
{
    public function __construct(
        private PrayerTimeService $prayerService
    ) {}

    public function index()
    {
        return view('prayer.index');
    }

    public function timings(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $method = $request->get('method', 2);

        if (! $lat || ! $lng) {
            return response()->json(['error' => 'Latitude and longitude required'], 400);
        }

        $timings = $this->prayerService->getTimings((float) $lat, (float) $lng, null, (int) $method);

        return response()->json($timings);
    }

    public function calendar(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $month = $request->get('month');
        $year = $request->get('year');
        $method = $request->get('method', 2);

        if (! $lat || ! $lng) {
            return response()->json(['error' => 'Latitude and longitude required'], 400);
        }

        $calendar = $this->prayerService->getMonthlyCalendar(
            (float) $lat,
            (float) $lng,
            $month ? (int) $month : null,
            $year ? (int) $year : null,
            (int) $method
        );

        return response()->json($calendar);
    }

    public function nextPrayer(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        $method = $request->get('method', 2);

        if (! $lat || ! $lng) {
            return response()->json(['error' => 'Latitude and longitude required'], 400);
        }

        $nextPrayer = $this->prayerService->getNextPrayer((float) $lat, (float) $lng, (int) $method);

        return response()->json($nextPrayer);
    }
}
