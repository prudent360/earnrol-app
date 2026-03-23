<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('company');
            $table->string('location')->nullable();
            $table->string('type')->default('full-time'); // full-time, part-time, contract, internship
            $table->string('salary_range')->nullable();
            $table->text('description')->nullable();
            $table->text('requirements')->nullable();
            $table->string('status')->default('active'); // active, closed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_listings');
    }
};
