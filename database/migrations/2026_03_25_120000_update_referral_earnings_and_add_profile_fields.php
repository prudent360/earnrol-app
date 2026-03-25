<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Allow multiple commissions per referred user (one per payment)
        Schema::table('referral_earnings', function (Blueprint $table) {
            $table->dropUnique(['referred_user_id']);
        });

        // Add profile fields to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('phone');
            $table->string('address')->nullable()->after('date_of_birth');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->string('postal_code')->nullable()->after('state');
            $table->string('country')->nullable()->after('postal_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'date_of_birth', 'address', 'city', 'state', 'postal_code', 'country']);
        });

        Schema::table('referral_earnings', function (Blueprint $table) {
            $table->unique('referred_user_id');
        });
    }
};
