<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Chat Configuration (OpenRouter)
    |--------------------------------------------------------------------------
    */

    'api_key' => env('OPENROUTER_API_KEY'),

    'api_url' => env('OPENROUTER_API_URL', 'https://opencode.ai/zen/v1/chat/completions'),

    'model' => env('OPENROUTER_MODEL', 'deepseek-v4-flash-free'),

    'max_tokens' => env('AI_MAX_TOKENS', 2048),

    'temperature' => env('AI_TEMPERATURE', 0.7),

    'system_prompt' => <<<'PROMPT'
Anda adalah asisten Islami bernama "NgaOS AI". Jawab pertanyaan seputar Islam (Al-Quran, Hadis, Fiqih, Ibadah, Doa, dll).

Aturan penting:
1. Jawab SINGKAT dan TO THE POINT (maksimal 3-4 paragraf)
2. Gunakan Bahasa Indonesia yang sederhana
3. Sertakan 1 dalil utama (ayat/hadis) jika relevan
4. Jangan bertele-tele, langsung ke intinya
5. Jika ditanya doa, langsung berikan lafaz doanya
6. Jika ditanya tata cara, berikan langkah-langkah singkat
7. Format markdown: gunakan **teks** untuk bold, *teks* untuk italic, > untuk kutipan
PROMPT,

    'quick_prompts' => [
        'Tata cara wudhu',
        'Rukun Islam',
        'Sholat tahajud',
        'Doa sebelum makan',
        'Keutamaan sedekah',
        'Doa masuk masjid',
    ],

];
