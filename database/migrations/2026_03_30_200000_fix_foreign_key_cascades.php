<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // coaching_bookings: add cascade on coaching_service_id and coaching_slot_id
        Schema::table('coaching_bookings', function (Blueprint $table) {
            $table->dropForeign(['coaching_service_id']);
            $table->dropForeign(['coaching_slot_id']);
            $table->foreign('coaching_service_id')->references('id')->on('coaching_services')->cascadeOnDelete();
            $table->foreign('coaching_slot_id')->references('id')->on('coaching_slots')->cascadeOnDelete();
        });

        // digital_products: add cascade on user_id
        Schema::table('digital_products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // affiliate_sales: make user columns nullable + nullOnDelete
        Schema::table('affiliate_sales', function (Blueprint $table) {
            $table->dropForeign(['affiliate_user_id']);
            $table->dropForeign(['buyer_user_id']);
            $table->dropForeign(['payment_id']);

            $table->unsignedBigInteger('affiliate_user_id')->nullable()->change();
            $table->unsignedBigInteger('buyer_user_id')->nullable()->change();
            $table->unsignedBigInteger('payment_id')->nullable()->change();

            $table->foreign('affiliate_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('buyer_user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('payment_id')->references('id')->on('payments')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('coaching_bookings', function (Blueprint $table) {
            $table->dropForeign(['coaching_service_id']);
            $table->dropForeign(['coaching_slot_id']);
            $table->foreign('coaching_service_id')->references('id')->on('coaching_services');
            $table->foreign('coaching_slot_id')->references('id')->on('coaching_slots');
        });

        Schema::table('digital_products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')->references('id')->on('users');
        });

        Schema::table('affiliate_sales', function (Blueprint $table) {
            $table->dropForeign(['affiliate_user_id']);
            $table->dropForeign(['buyer_user_id']);
            $table->dropForeign(['payment_id']);
            $table->foreign('affiliate_user_id')->references('id')->on('users');
            $table->foreign('buyer_user_id')->references('id')->on('users');
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }
};
