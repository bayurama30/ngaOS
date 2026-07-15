<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Prayer Times API Configuration
    |--------------------------------------------------------------------------
    */

    'api_url' => env('PRAYER_API_URL', 'https://api.aladhan.com/v1'),

    'cache_ttl' => env('PRAYER_CACHE_TTL', 3600),

    'calculation_methods' => [
        1 => 'University of Islamic Sciences, Karachi',
        2 => 'Islamic Society of North America (ISNA)',
        3 => 'Muslim World League',
        4 => 'Umm Al-Qura University, Makkah',
        5 => 'Egyptian General Authority of Survey',
        7 => 'Institute of Geophysics, University of Tehran',
        8 => 'Gulf Region',
        9 => 'Kuwait',
        10 => 'Qatar',
        11 => 'Majlis Ugama Islam Singapura',
        12 => 'Union Organization Islamic de France',
        13 => 'Diyanet İşleri Başkanlığı, Turkey',
        14 => 'Spiritual Administration of Muslims of Russia',
        15 => 'Moonsighting Committee Worldwide',
    ],

    'default_method' => env('PRAYER_DEFAULT_METHOD', 2),

    'qibla_kaaba' => [
        'lat' => 21.4225,
        'lng' => 39.8262,
    ],

];
