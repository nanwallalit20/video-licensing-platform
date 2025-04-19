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
        Schema::create('catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('title_id')->constrained('titles')->cascadeOnDelete();
            $table->string('imdb_url')->nullable();
            $table->string('tmdb_url')->nullable();
            $table->string('vendor_id')->nullable();
            $table->string('production_company')->nullable();
            $table->string('copyright_line')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('public_domain')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogs');
    }
};
