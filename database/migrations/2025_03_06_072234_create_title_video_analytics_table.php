<?php

use App\Enums\Devices;
use App\Enums\TitleVideoAnalytics;
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
        Schema::create('title_video_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('title_id')->constrained('titles')->onDelete('cascade');
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');
            $table->foreignId('platform_id')->constrained('ott_platforms')->onDelete('cascade');
            $table->integer('total_views')->default(0);
            $table->integer('unique_views')->default(0);
            $table->integer('total_watch_time')->default(0); // in seconds
            $table->decimal('completion_rate', 5, 2)->default(0); // percentage
            $table->enum('device_type', array_column(Devices::cases(), 'value'))->nullable();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('title_video_analytics');
    }
};
