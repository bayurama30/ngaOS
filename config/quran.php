<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Quran API Configuration
    |--------------------------------------------------------------------------
    */

    'api_url' => env('QURAN_API_URL', 'https://api.quran.com/api/v4'),

    'cache_ttl' => env('QURAN_CACHE_TTL', 86400 * 30),

    'default_translation' => env('QURAN_DEFAULT_TRANSLATION', 33),

    'reciters' => [
        ['id' => 7, 'name' => 'Mishary Rashid Alafasy'],
        ['id' => 1, 'name' => 'Abdullah Basfar'],
        ['id' => 2, 'name' => 'Abdurrahman as-Sudais'],
        ['id' => 3, 'name' => 'Abu Bakr al Shatri'],
        ['id' => 4, 'name' => 'Hani ar-Rifai'],
        ['id' => 5, 'name' => 'Mahmoud Khalil Al-Husary'],
        ['id' => 6, 'name' => 'Muhammad Ayyub'],
    ],

    'translations' => [
        ['id' => 33, 'name' => 'Bahasa Indonesia', 'language' => 'id'],
        ['id' => 20, 'name' => 'English (Sahih International)', 'language' => 'en'],
        ['id' => 101, 'name' => 'Tafsir Jalalayn', 'language' => 'ar'],
    ],

];
