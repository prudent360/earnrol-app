<?php

namespace App\Console\Commands;

use App\Models\Lesson;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class FixLessonSlugs extends Command
{
    protected $signature = 'lessons:fix-slugs';
    protected $description = 'Generate slugs for lessons that are missing them';

    public function handle(): void
    {
        $lessons = Lesson::whereNull('slug')->orWhere('slug', '')->get();

        if ($lessons->isEmpty()) {
            $this->info('All lessons already have slugs. Nothing to fix.');
            return;
        }

        foreach ($lessons as $lesson) {
            $lesson->slug = Str::slug($lesson->title);
            $lesson->save();
            $this->line("  ✓ {$lesson->title} → {$lesson->slug}");
        }

        $this->info("Fixed {$lessons->count()} lesson(s).");
    }
}
