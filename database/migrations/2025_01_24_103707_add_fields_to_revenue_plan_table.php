<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('revenue_plan', function (Blueprint $table) {
            $table->string('email')->nullable();
            $table->date('date')->nullable();
            $table->string('cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('revenue_plan', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('date');
            $table->dropColumn('cost');
        });
    }
};
