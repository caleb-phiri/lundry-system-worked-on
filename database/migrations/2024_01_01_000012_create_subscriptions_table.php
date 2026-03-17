<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->enum('frequency', ['weekly', 'biweekly', 'monthly']);
            $table->enum('basket_size', ['small', 'medium', 'large']);
            $table->decimal('price', 10, 2);
            $table->json('preferences')->nullable();
            $table->datetime('next_pickup_date');
            $table->datetime('last_pickup_date')->nullable();
            $table->enum('status', ['active', 'paused', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};