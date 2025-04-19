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
        Schema::table('buyers', function (Blueprint $table) {
            $table->string('whatsapp_number', 15)->nullable()->after('phone_number');
            $table->boolean('terms_and_conditons')->default(0)->after('additional_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->dropColumn('whatsapp_number');
            $table->dropColumn('terms_and_conditions');
        });
    }
};
