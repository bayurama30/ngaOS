<?php

namespace App\Http\Controllers;

use App\Services\HadithService;
use App\Services\MuslimApiService;
use App\Services\PrayerTimeService;
use App\Services\QiblaService;
use App\Services\QuranService;
use Illuminate\Http\Request;

class MuslimController extends Controller
{
    public function __construct(
        private MuslimApiService $muslimApi,
        private PrayerTimeService $prayerService,
        private QiblaService $qiblaService,
        private QuranService $quranService,
        private HadithService $hadithService,
    ) {}

    public function searchCity(Request $request)
    {
        $keyword = $request->get('q', '');

        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $results = $this->prayerService->searchCity($keyword);

        return response()->json($results ?? []);
    }

    public function prayerSchedule(Request $request)
    {
        $cityId = $request->get('city_id');
        $timezone = $request->get('tz', config('muslim.default_timezone'));

        if (!$cityId) {
            return response()->json(['error' => 'city_id required'], 400);
        }

        $schedule = $this->prayerService->getTodaySchedule($cityId, $timezone);

        return response()->json($schedule);
    }

    public function qiblaDirection(Request $request)
    {
        $lat = $request->get('lat');
        $lng = $request->get('lng');

        if (!$lat || !$lng) {
            return response()->json(['error' => 'lat and lng required'], 400);
        }

        $direction = $this->qiblaService->getDirection((float) $lat, (float) $lng);
        $distance = $this->qiblaService->getDistance((float) $lat, (float) $lng);

        return response()->json([
            'direction' => $direction['direction'] ?? 0,
            'distance' => $distance,
        ]);
    }

    public function todayHijri(Request $request)
    {
        $timezone = $request->get('tz', config('muslim.default_timezone'));

        $data = $this->muslimApi->get('/cal/today', [
            'tz' => $timezone,
        ]);

        return response()->json($data);
    }

    public function randomHadis()
    {
        $hadis = $this->hadithService->getRandomHadith();

        return response()->json($hadis);
    }

    public function getHadis(string $id)
    {
        $hadis = $this->hadithService->getHadith((int) $id);

        return response()->json($hadis);
    }

    public function exploreHadis(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 10);

        $data = $this->hadithService->explore((int) $page, (int) $limit);

        return response()->json($data);
    }

    public function searchHadis(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 3) {
            return response()->json(['hadis' => []]);
        }

        $data = $this->hadithService->search($query);

        return response()->json($data);
    }

    public function randomAyah()
    {
        $ayah = $this->quranService->getRandomAyah();

        return response()->json($ayah);
    }

    public function surahList()
    {
        $surahs = $this->quranService->getSurahList();

        return response()->json($surahs);
    }

    public function getSurah(string $number)
    {
        $surah = $this->quranService->getSurah((int) $number);

        return response()->json($surah);
    }

    public function allCities()
    {
        $cities = $this->prayerService->getAllCities();

        return response()->json($cities ?? []);
    }
}
