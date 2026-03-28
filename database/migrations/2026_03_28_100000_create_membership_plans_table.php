<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('billing_interval', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->text('features')->nullable();
            $table->unsignedInteger('max_subscribers')->nullable();
            $table->text('welcome_message')->nullable();
            $table->string('status')->default('draft');
            $table->string('approval_status')->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->string('stripe_product_id')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_plans');
    }
};
