<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_uuid')->unique();
            $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->string('currency', 3)->default('USD')->nullable();
            $table->enum('order_status', array_column(OrderStatus::cases(), 'value'))->default(OrderStatus::Pending->value);
            $table->enum('payment_status', array_column(PaymentStatus::cases(), 'value'))->default(PaymentStatus::Pending->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
