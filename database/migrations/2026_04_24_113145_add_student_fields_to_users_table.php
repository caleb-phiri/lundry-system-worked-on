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
        Schema::table('users', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('users', 'hostel_name')) {
                $table->string('hostel_name')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'room_number')) {
                $table->string('room_number')->nullable()->after('hostel_name');
            }
            if (!Schema::hasColumn('users', 'student_id')) {
                $table->string('student_id')->nullable()->after('room_number');
            }
            if (!Schema::hasColumn('users', 'alternative_phone')) {
                $table->string('alternative_phone')->nullable()->after('student_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'hostel_name',
                'room_number',
                'student_id',
                'alternative_phone'
            ]);
        });
    }
};