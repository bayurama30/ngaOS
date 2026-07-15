<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hadith_cache', function (Blueprint $table) {
            $table->id();
            $table->string('book_slug');
            $table->unsignedInteger('hadith_number');
            $table->text('arabic_text')->nullable();
            $table->text('translation');
            $table->string('narrator')->nullable();
            $table->string('grade')->nullable();
            $table->timestamps();

            $table->unique(['book_slug', 'hadith_number']);
            $table->index('book_slug');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hadith_cache');
    }
};
