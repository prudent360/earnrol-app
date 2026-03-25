<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('digital_product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('download_count')->default(0);
            $table->timestamp('purchased_at');
            $table->timestamps();

            $table->unique(['user_id', 'digital_product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_purchases');
    }
};
