<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users');
            $table->foreignId('referred_id')->constrained('users');
            $table->string('referral_code');
            $table->enum('status', ['pending', 'completed', 'expired'])->default('pending');
            $table->decimal('reward_amount', 10, 2)->default(20.00);
            $table->boolean('reward_issued')->default(false);
            $table->datetime('reward_issued_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('referrals');
    }
};