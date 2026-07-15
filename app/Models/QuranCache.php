<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuranCache extends Model
{
    protected $table = 'quran_cache';

    protected $fillable = [
        'surah_number',
        'ayah_number',
        'arabic_text',
        'translation_id',
        'translation_en',
        'audio_url',
        'tafsir',
    ];

    protected function casts(): array
    {
        return [
            'surah_number' => 'integer',
            'ayah_number' => 'integer',
        ];
    }
}
