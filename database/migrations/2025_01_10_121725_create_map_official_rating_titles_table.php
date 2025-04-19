<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('map_official_rating_titles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('official_rating_id')->constrained('official_ratings')->cascadeOnDelete();
            $table->foreignId('title_id')->constrained('titles')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('map_official_rating_titles');
    }
};
