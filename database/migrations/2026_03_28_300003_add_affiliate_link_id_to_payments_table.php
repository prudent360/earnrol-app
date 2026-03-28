<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('affiliate_link_id')->nullable()->after('is_renewal')
                  ->constrained('affiliate_links')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['affiliate_link_id']);
            $table->dropColumn('affiliate_link_id');
        });
    }
};
