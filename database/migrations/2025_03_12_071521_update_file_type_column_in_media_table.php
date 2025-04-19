<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\MediaTypes;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            DB::statement("ALTER TABLE media MODIFY COLUMN file_type ENUM('1','2','3','4','5','6','7') NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            DB::statement("ALTER TABLE media MODIFY COLUMN file_type ENUM('1', '2', '3', '4','5','6') NOT NULL");
        });
    }
};
