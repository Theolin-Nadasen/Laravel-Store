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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Your unique ID that you send to the payment gateway (e.g., 'ORD-XYZ123')
            $table->string('order_id')->nullable();

            $table->boolean('is_complete')->default(false);
            $table->boolean('payment_status')->default(false);

            $table->boolean('is_delivery');
            $table->string('customer_name');
            $table->string('contact_phone');

            $table->text('delivery_address')->nullable();

            $table->json('items');

            $table->decimal('total_amount', 8, 2);

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
