<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category'); // cloud-computing, devops-cicd, cybersecurity, data-engineering, linux, networking
            $table->string('level')->default('beginner'); // beginner, intermediate, advanced
            $table->decimal('price', 8, 2)->default(0);
            $table->boolean('is_free')->default(true);
            $table->integer('duration_hours')->default(0);
            $table->integer('lesson_count')->default(0);
            $table->decimal('rating', 3, 1)->default(0.0);
            $table->integer('student_count')->default(0);
            $table->string('badge')->nullable(); // Popular, Hot, New, Trending
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('draft'); // draft, published
            $table->string('thumbnail')->nullable();
            $table->string('icon_color')->default('#e05a3a');
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
