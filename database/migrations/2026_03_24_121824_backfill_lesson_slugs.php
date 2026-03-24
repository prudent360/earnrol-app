<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $lessons = DB::table('lessons')->whereNull('slug')->orWhere('slug', '')->get();

        foreach ($lessons as $lesson) {
            $base = Str::slug($lesson->title);
            $slug = $base;
            $i = 1;

            while (DB::table('lessons')->where('slug', $slug)->where('id', '!=', $lesson->id)->exists()) {
                $slug = $base . '-' . $i++;
            }

            DB::table('lessons')->where('id', $lesson->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void {}
};
