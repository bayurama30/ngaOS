<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HadithCache extends Model
{
    protected $table = 'hadith_cache';

    protected $fillable = [
        'book_slug',
        'hadith_number',
        'arabic_text',
        'translation',
        'narrator',
        'grade',
    ];

    protected function casts(): array
    {
        return [
            'hadith_number' => 'integer',
        ];
    }
}
