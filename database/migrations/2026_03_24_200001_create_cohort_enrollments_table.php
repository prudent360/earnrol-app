<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cohort_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cohort_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('enrolled_at');
            $table->timestamps();
            $table->unique(['user_id', 'cohort_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cohort_enrollments');
    }
};
