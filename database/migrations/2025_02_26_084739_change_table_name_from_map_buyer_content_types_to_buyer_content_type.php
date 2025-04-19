<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::rename('map_buyer_content_types', 'buyer_content_type');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::rename('buyer_content_type', 'map_buyer_content_types');
    }
};
