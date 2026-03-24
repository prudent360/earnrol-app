<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cohorts', function (Blueprint $table) {
            $table->string('facilitator_name')->nullable()->after('max_students');
            $table->text('facilitator_bio')->nullable()->after('facilitator_name');
            $table->string('facilitator_image')->nullable()->after('facilitator_bio');
            $table->string('schedule')->nullable()->after('facilitator_image');
            $table->text('what_you_will_learn')->nullable()->after('schedule');
            $table->text('prerequisites')->nullable()->after('what_you_will_learn');
            $table->string('cover_image')->nullable()->after('prerequisites');
        });
    }

    public function down(): void
    {
        Schema::table('cohorts', function (Blueprint $table) {
            $table->dropColumn([
                'facilitator_name', 'facilitator_bio', 'facilitator_image',
                'schedule', 'what_you_will_learn', 'prerequisites', 'cover_image',
            ]);
        });
    }
};
