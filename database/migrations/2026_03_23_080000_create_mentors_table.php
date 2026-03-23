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
        Schema::create('mentors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('role_title');
            $table->string('company');
            $table->text('bio')->nullable();
            $table->string('avatar_text', 5)->nullable(); // e.g. "JK"
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->integer('sessions_count')->default(0);
            $table->json('expertise')->nullable();
            $table->string('price_label')->default('Free'); // e.g. "$30/session"
            $table->string('icon_color')->default('#e05a3a');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentors');
    }
};
