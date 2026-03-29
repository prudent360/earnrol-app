<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('pageable_type');
            $table->unsignedBigInteger('pageable_id');
            $table->string('template')->default('starter');
            $table->json('content')->nullable();
            $table->boolean('is_published')->default(false);
            $table->string('custom_slug')->unique();
            $table->timestamps();

            $table->index(['pageable_type', 'pageable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_pages');
    }
};
