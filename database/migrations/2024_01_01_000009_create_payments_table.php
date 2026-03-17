<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['mtn', 'airtel', 'zamtel', 'wallet']);
            $table->enum('payment_type', ['prepaid', 'postpaid']);
            $table->string('transaction_id')->unique();
            $table->string('provider_reference')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'refunded']);
            $table->json('provider_response')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};