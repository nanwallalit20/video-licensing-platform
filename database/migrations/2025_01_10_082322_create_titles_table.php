<?php

use App\Enums\TitleStatus;
use App\Enums\TitleType;
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
        Schema::create('titles', function (Blueprint $table) {
            $table->id(); // Primary key with auto-increment
            $table->foreignId('user_id')->constrained('users') // Reference the 'users' table
                ->cascadeOnDelete(); // Delete titles when the user is deleted
            $table->string('slug')->unique();
            $table->string('name');
            $table->enum('status', array_column(TitleStatus::cases(), 'value'));
            $table->string('reason')->nullable();
            $table->enum('type', array_column( TitleType::cases(), 'value'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titles');
    }
};
