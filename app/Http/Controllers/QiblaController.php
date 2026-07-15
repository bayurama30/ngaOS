<?php

namespace App\Http\Controllers;

use App\Services\QiblaService;
use Illuminate\Http\Request;

class QiblaController extends Controller
{
    public function __construct(
        private QiblaService $qiblaService
    ) {}

    public function index()
    {
        return view('qibla.index');
    }

    public function direction(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');

        if (! $lat || ! $lng) {
            return response()->json(['error' => 'Latitude and longitude required'], 400);
        }

        $direction = $this->qiblaService->getDirection((float) $lat, (float) $lng);
        $distance = $this->qiblaService->getDistance((float) $lat, (float) $lng);

        return response()->json([
            'direction' => $direction,
            'distance' => $distance,
        ]);
    }
}
