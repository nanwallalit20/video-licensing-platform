<?php

use App\Enums\TitleFestival;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        $types = array_column(array_values(TitleFestival::cases()), 'value');

        Schema::table('map_festival_titles', function (Blueprint $table) use ($types) {
            $table->enum('type', $types)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('map_festival_titles', function (Blueprint $table) {
            $table->enum('type', ['accepted', 'won'])->change();
        });
    }
};
