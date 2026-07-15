<?php

return [

    /*
    |--------------------------------------------------------------------------
    | API Muslim Configuration
    |--------------------------------------------------------------------------
    | API Komprehensif untuk kebutuhan Muslim di Indonesia
    | Sumber: api.myquran.com
    */

    'api_url' => env('MUSLIM_API_URL', 'https://api.myquran.com/v3'),

    'cache_ttl' => env('MUSLIM_CACHE_TTL', 86400),

    'default_timezone' => env('MUSLIM_DEFAULT_TZ', 'Asia/Jakarta'),

    'timezones' => [
        'Asia/Jakarta' => 'WIB (Jakarta, Bandung, Semarang, Surabaya)',
        'Asia/Makassar' => 'WITA (Makassar, Denpasar, Banjarmasin)',
        'Asia/Jayapura' => 'WIT (Jayapura, Manokwari, Sorong)',
    ],

];
