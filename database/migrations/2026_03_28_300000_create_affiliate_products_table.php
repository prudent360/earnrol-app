<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_products', function (Blueprint $table) {
            $table->id();
            $table->string('affiliable_type');
            $table->unsignedBigInteger('affiliable_id');
            $table->boolean('affiliate_enabled')->default(false);
            $table->decimal('commission_percentage', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['affiliable_type', 'affiliable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_products');
    }
};
