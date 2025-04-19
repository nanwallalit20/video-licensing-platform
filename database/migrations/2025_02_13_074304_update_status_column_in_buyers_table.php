<?php

use App\Enums\BuyerStatus;
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
            $table->enum('status', array_column(BuyerStatus::cases(), 'value'))->default(BuyerStatus::Pending->value)->change();
        });
        DB::table('buyers')->where('status', 0)->update(['status' => BuyerStatus::Rejected->value]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buyers', function (Blueprint $table) {
            $table->enum('status', [0,1,2])->default('0')->change();
            DB::table('buyers')->where('status', 3)->update(['status' => 0]);
        });
    }
};
