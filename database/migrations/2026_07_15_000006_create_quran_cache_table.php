<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quran_cache', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('surah_number');
            $table->unsignedInteger('ayah_number');
            $table->text('arabic_text');
            $table->text('translation_id')->nullable();
            $table->text('translation_en')->nullable();
            $table->string('audio_url')->nullable();
            $table->text('tafsir')->nullable();
            $table->timestamps();

            $table->unique(['surah_number', 'ayah_number']);
            $table->index('surah_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quran_cache');
    }
};
