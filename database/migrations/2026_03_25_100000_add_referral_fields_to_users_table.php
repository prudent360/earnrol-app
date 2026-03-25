<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code')->unique()->nullable()->after('role');
            $table->foreignId('referred_by')->nullable()->after('referral_code')
                  ->constrained('users')->nullOnDelete();
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('referred_by');
            $table->string('bank_name')->nullable()->after('wallet_balance');
            $table->string('bank_account_name')->nullable()->after('bank_name');
            $table->string('bank_account_number')->nullable()->after('bank_account_name');
            $table->string('bank_sort_code')->nullable()->after('bank_account_number');
        });

        // Backfill referral codes for existing users
        foreach (\App\Models\User::whereNull('referral_code')->cursor() as $user) {
            do {
                $code = Str::upper(Str::random(8));
            } while (\App\Models\User::where('referral_code', $code)->exists());
            $user->update(['referral_code' => $code]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn([
                'referral_code', 'referred_by', 'wallet_balance',
                'bank_name', 'bank_account_name', 'bank_account_number', 'bank_sort_code',
            ]);
        });
    }
};
