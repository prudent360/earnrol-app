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
        Schema::create('mentorship_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('mentors')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('scheduled_at');
            $table->integer('duration_minutes')->default(45);
            $table->string('status')->default('pending'); // pending, confirmed, completed, cancelled
            $table->string('topic')->nullable();
            $table->text('notes')->nullable();
            $table->string('meeting_link')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentorship_sessions');
    }
};
