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
            $table->string('username')->unique()->nullable()->after('name');
            $table->text('bio')->nullable()->after('email');
        });

        // Generate usernames for existing users
        foreach (\App\Models\User::whereNull('username')->cursor() as $user) {
            $base = Str::slug($user->name);
            if (empty($base)) {
                $base = 'user';
            }
            $username = $base;
            $counter = 1;
            while (\App\Models\User::where('username', $username)->exists()) {
                $username = $base . '-' . $counter;
                $counter++;
            }
            $user->update(['username' => $username]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'bio']);
        });
    }
};
