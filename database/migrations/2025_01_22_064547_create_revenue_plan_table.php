<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\RevenuePlanType;
use App\Enums\RevenuePlanStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('revenue_plan', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('title_id')->unsigned();
            $table->enum('type', array_column(RevenuePlanType::cases(), 'value'));
            $table->enum('status', array_column(RevenuePlanStatus::cases(), 'value'));
            $table->string('name', 50);
            $table->text('description');
            $table->string('remarks', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('revenue_plan');
    }
};
