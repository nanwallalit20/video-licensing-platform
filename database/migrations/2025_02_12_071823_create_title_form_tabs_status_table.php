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
        Schema::create('title_form_tabs_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('title_id')->constrained('titles')->cascadeOnDelete();
            $table->boolean('profile_tab')->default(false);
            $table->boolean('contact_tab')->default(false);
            $table->boolean('document_tab')->default(false);
            $table->boolean('video_season_tab')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_form_tabs_status');
    }
};
