<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_earnings', function (Blueprint $table) {
            // Allow manual credits (payment_id null)
            $table->unsignedBigInteger('payment_id')->nullable()->change();
            $table->string('note')->nullable()->after('commission_rate');
        });
    }

    public function down(): void
    {
        Schema::table('referral_earnings', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
};
