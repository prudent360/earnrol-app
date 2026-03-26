<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cohort_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cohort_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('cohort_discussions')->cascadeOnDelete();
            $table->text('body');
            $table->timestamps();

            $table->index(['cohort_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cohort_discussions');
    }
};
