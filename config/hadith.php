<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Hadith API Configuration
    |--------------------------------------------------------------------------
    */

    'api_url' => env('HADITH_API_URL', 'https://cdn.jsdelivr.net/gh/fawazahmed0/hadith-api@1'),

    'cache_ttl' => env('HADITH_CACHE_TTL', 86400 * 30),

    'books' => [
        'eng-bukhari' => [
            'name' => 'Sahih Bukhari',
            'author' => 'Imam Bukhari',
            'total_hadith' => 7563,
        ],
        'eng-muslim' => [
            'name' => 'Sahih Muslim',
            'author' => 'Imam Muslim',
            'total_hadith' => 7500,
        ],
        'eng-abudawud' => [
            'name' => 'Sunan Abu Dawud',
            'author' => 'Imam Abu Dawud',
            'total_hadith' => 5274,
        ],
        'eng-tirmidhi' => [
            'name' => 'Jami at-Tirmidhi',
            'author' => 'Imam Tirmidhi',
            'total_hadith' => 3956,
        ],
        'eng-nasai' => [
            'name' => 'Sunan an-Nasai',
            'author' => 'Imam an-Nasai',
            'total_hadith' => 5761,
        ],
        'eng-ibnmajah' => [
            'name' => 'Sunan Ibn Majah',
            'author' => 'Imam Ibn Majah',
            'total_hadith' => 4341,
        ],
    ],

];
