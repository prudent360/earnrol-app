<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->nullable();
            $table->string('github_url')->nullable();
            $table->string('live_url')->nullable();
            $table->integer('points')->default(0);
            $table->string('difficulty')->default('intermediate'); // beginner, intermediate, advanced
            $table->string('tags')->nullable(); // comma separated or json
            $table->string('status')->default('pending'); // pending, active, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
