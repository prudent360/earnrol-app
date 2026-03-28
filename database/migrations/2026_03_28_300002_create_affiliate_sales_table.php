<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affiliate_link_id')->constrained()->cascadeOnDelete();
            $table->foreignId('affiliate_user_id')->constrained('users');
            $table->foreignId('buyer_user_id')->constrained('users');
            $table->foreignId('payment_id')->constrained();
            $table->decimal('sale_amount', 10, 2);
            $table->decimal('affiliate_commission', 10, 2);
            $table->decimal('admin_commission', 10, 2)->default(0);
            $table->decimal('creator_amount', 10, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('admin_fee_rate', 5, 2)->default(0);
            $table->string('status')->default('completed');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_sales');
    }
};
