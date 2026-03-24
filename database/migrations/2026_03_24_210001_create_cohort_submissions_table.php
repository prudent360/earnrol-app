<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cohort_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cohort_material_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->text('notes')->nullable();
            $table->string('grade')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamps();

            $table->unique(['cohort_material_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cohort_submissions');
    }
};
