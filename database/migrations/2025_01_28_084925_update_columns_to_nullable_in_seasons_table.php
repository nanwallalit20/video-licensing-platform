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
        Schema::table('seasons', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->text('synopsis')->nullable()->change();
            $table->date('release_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->text('synopsis')->nullable(false)->change();
            $table->date('release_date')->nullable(false)->change();
        });
    }
};
