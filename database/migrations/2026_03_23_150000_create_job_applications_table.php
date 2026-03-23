<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $col) {
            $col->id();
            $col->foreignId('job_id')->constrained('job_listings')->onDelete('cascade');
            $col->foreignId('user_id')->constrained()->onDelete('cascade');
            $col->string('resume_path')->nullable();
            $col->text('cover_letter')->nullable();
            $col->string('status')->default('pending');
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
