<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->boolean('is_online')->default(false);
            $table->boolean('on_duty')->default(false);
            $table->timestamp('last_updated_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('driver_locations');
    }
};