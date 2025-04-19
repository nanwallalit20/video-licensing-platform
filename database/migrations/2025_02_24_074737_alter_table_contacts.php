<?php

use App\Enums\TitleContact;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {

        $types = array_column(array_values(TitleContact::cases()), 'value');

        Schema::table('contacts', function (Blueprint $table) use ($types) {
            $table->enum('type', $types)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('contacts', function (Blueprint $table) {
            $table->enum('type', ['0', '1'])->change();
        });
    }
};
