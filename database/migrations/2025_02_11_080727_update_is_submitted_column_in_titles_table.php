<?php

use App\Enums\TitleCompletion;
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
        Schema::table('titles', function (Blueprint $table) {
            $table->enum('isSubmitted', array_column(TitleCompletion::cases(), 'value'))->default(2)->change();
        });
        DB::table('titles')->update(['isSubmitted' => TitleCompletion::Pending->value]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titles', function (Blueprint $table) {

            $table->enum('isSubmitted', ['0', '1'])->default('0')->change();
            DB::table('titles')->update(['isSubmitted' => '0']);
        });
    }
};
