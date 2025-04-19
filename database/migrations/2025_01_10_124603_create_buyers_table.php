<?php

use App\Enums\AcquisitionPreference;
use App\Enums\ContentDuration;
use App\Enums\ContentUsage;
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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('company_name');
            $table->string('job_title');
            $table->string('email')->unique();
            $table->string('phone_number');
            $table->string('target_audience');
            $table->string('territories');
            $table->decimal('budget', 15, 2); // Assuming budget is a monetary value
            $table->enum('content_usage',array_column(ContentUsage::cases(), 'value'));
            $table->enum('content_duration',array_column(ContentDuration::cases(), 'value'));
            $table->enum('acquisition_preferences',array_column(AcquisitionPreference::cases(), 'value'));
            $table->text('additional_details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
