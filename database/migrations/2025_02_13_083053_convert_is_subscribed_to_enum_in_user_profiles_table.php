<?php

use App\Enums\SubscriptionStatus;
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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->enum('is_subscribed', array_column(SubscriptionStatus::cases(), 'value'))
                ->default(SubscriptionStatus::NOT_SUBSCRIBED->value)
                ->change();
            DB::table('user_profiles')->where('is_subscribed', 0)->update(['is_subscribed' => SubscriptionStatus::NOT_SUBSCRIBED->value]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->boolean('is_subscribed')->default(0)->change();
        });
    }
};
