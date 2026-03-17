<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('client_id')->constrained('users');
            $table->foreignId('driver_id')->nullable()->constrained('users');
            $table->foreignId('basket_id')->constrained();
            $table->foreignId('pickup_location_id')->constrained('locations');
            $table->foreignId('delivery_location_id')->constrained('locations');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('surcharges', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->decimal('wallet_used', 10, 2)->default(0);
            $table->enum('payment_method', ['mtn', 'airtel', 'zamtel', 'wallet']);
            $table->enum('payment_type', ['prepaid', 'postpaid']);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded', 'partially_paid'])->default('pending');
            $table->string('transaction_reference')->nullable();
            $table->enum('status', [
                'pickup_scheduled',
                'driver_assigned',
                'collected',
                'in_laundry',
                'out_for_delivery',
                'delivered',
                'cancelled'
            ])->default('pickup_scheduled');
            $table->datetime('pickup_scheduled_at');
            $table->datetime('pickup_completed_at')->nullable();
            $table->datetime('delivery_scheduled_at')->nullable();
            $table->datetime('delivery_completed_at')->nullable();
            $table->text('special_instructions')->nullable();
            $table->json('special_items')->nullable();
            $table->json('tracking_history')->nullable();
            $table->boolean('is_subscription')->default(false);
            $table->timestamps();
            
            $table->index('order_number');
            $table->index('status');
            $table->index('payment_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};