<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lesson;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title'          => 'AWS Solutions Architect — Associate',
                'description'    => 'Master AWS cloud architecture. Build real-world projects, pass the SAA-C03 exam, and land a cloud engineering job.',
                'category'       => 'cloud-computing',
                'level'          => 'intermediate',
                'price'          => 0,
                'is_free'        => true,
                'duration_hours' => 24,
                'lesson_count'   => 22,
                'rating'         => 4.9,
                'student_count'  => 2400,
                'badge'          => 'Popular',
                'is_featured'    => true,
                'status'         => 'published',
                'icon_color'     => '#e05a3a',
            ],
            [
                'title'          => 'Docker & Kubernetes Mastery',
                'description'    => 'Learn containerisation and orchestration end-to-end. Deploy scalable microservices with Docker and Kubernetes.',
                'category'       => 'devops-cicd',
                'level'          => 'intermediate',
                'price'          => 49.99,
                'is_free'        => false,
                'duration_hours' => 18,
                'lesson_count'   => 20,
                'rating'         => 4.8,
                'student_count'  => 1800,
                'badge'          => 'Hot',
                'is_featured'    => false,
                'status'         => 'published',
                'icon_color'     => '#3b82f6',
            ],
            [
                'title'          => 'CompTIA Security+ Prep',
                'description'    => 'Prepare for the CompTIA Security+ certification with hands-on labs and practice exams.',
                'category'       => 'cybersecurity',
                'level'          => 'beginner',
                'price'          => 39.99,
                'is_free'        => false,
                'duration_hours' => 20,
                'lesson_count'   => 18,
                'rating'         => 4.7,
                'student_count'  => 956,
                'badge'          => 'New',
                'is_featured'    => false,
                'status'         => 'published',
                'icon_color'     => '#22c55e',
            ],
            [
                'title'          => 'Linux for Cloud Engineers',
                'description'    => 'Everything you need to know about Linux to become a confident cloud engineer.',
                'category'       => 'linux',
                'level'          => 'beginner',
                'price'          => 0,
                'is_free'        => true,
                'duration_hours' => 12,
                'lesson_count'   => 15,
                'rating'         => 4.8,
                'student_count'  => 3100,
                'badge'          => null,
                'is_featured'    => false,
                'status'         => 'published',
                'icon_color'     => '#f59e0b',
            ],
            [
                'title'          => 'Apache Kafka & Data Pipelines',
                'description'    => 'Build real-time data pipelines with Kafka. Learn producers, consumers, streams, and deployment.',
                'category'       => 'data-engineering',
                'level'          => 'advanced',
                'price'          => 59.99,
                'is_free'        => false,
                'duration_hours' => 22,
                'lesson_count'   => 24,
                'rating'         => 4.9,
                'student_count'  => 712,
                'badge'          => 'Trending',
                'is_featured'    => false,
                'status'         => 'published',
                'icon_color'     => '#8b5cf6',
            ],
            [
                'title'          => 'Terraform Infrastructure as Code',
                'description'    => 'Automate cloud infrastructure provisioning with Terraform across AWS, GCP, and Azure.',
                'category'       => 'devops-cicd',
                'level'          => 'intermediate',
                'price'          => 49.99,
                'is_free'        => false,
                'duration_hours' => 16,
                'lesson_count'   => 19,
                'rating'         => 4.7,
                'student_count'  => 1200,
                'badge'          => null,
                'is_featured'    => false,
                'status'         => 'published',
                'icon_color'     => '#06b6d4',
            ],
            [
                'title'          => 'Ethical Hacking & Penetration Testing',
                'description'    => 'Learn offensive security techniques used by real-world penetration testers and bug bounty hunters.',
                'category'       => 'cybersecurity',
                'level'          => 'advanced',
                'price'          => 69.99,
                'is_free'        => false,
                'duration_hours' => 30,
                'lesson_count'   => 32,
                'rating'         => 4.9,
                'student_count'  => 2100,
                'badge'          => 'Popular',
                'is_featured'    => false,
                'status'         => 'published',
                'icon_color'     => '#ef4444',
            ],
            [
                'title'          => 'Python for Data Engineering',
                'description'    => 'Use Python to build ETL pipelines, work with databases, and automate data workflows.',
                'category'       => 'data-engineering',
                'level'          => 'beginner',
                'price'          => 0,
                'is_free'        => true,
                'duration_hours' => 14,
                'lesson_count'   => 16,
                'rating'         => 4.6,
                'student_count'  => 4500,
                'badge'          => null,
                'is_featured'    => false,
                'status'         => 'published',
                'icon_color'     => '#3b82f6',
            ],
            [
                'title'          => 'Cisco CCNA Networking Fundamentals',
                'description'    => 'Get CCNA-ready with hands-on Cisco labs covering routing, switching, VLANs, and subnetting.',
                'category'       => 'networking',
                'level'          => 'beginner',
                'price'          => 44.99,
                'is_free'        => false,
                'duration_hours' => 28,
                'lesson_count'   => 30,
                'rating'         => 4.8,
                'student_count'  => 1650,
                'badge'          => 'New',
                'is_featured'    => false,
                'status'         => 'published',
                'icon_color'     => '#10b981',
            ],
        ];

        foreach ($courses as $data) {
            $course = Course::firstOrCreate(['title' => $data['title']], $data);
            
            // Add sample curriculum if no chapters exist
            if ($course->chapters()->count() === 0) {
                $chapter = Chapter::create([
                    'course_id' => $course->id,
                    'title' => 'Getting Started',
                    'order' => 1
                ]);

                Lesson::create([
                    'chapter_id' => $chapter->id,
                    'title' => 'Introduction to ' . $course->title,
                    'slug' => Str::slug('Introduction to ' . $course->title),
                    'video_url' => 'https://www.youtube.com/watch?v=Ia-UEYY8koE',
                    'duration_minutes' => 10,
                    'order' => 1,
                    'is_preview' => true,
                    'content' => 'Welcome to this course! In this lesson, we will cover the basics.'
                ]);

                Lesson::create([
                    'chapter_id' => $chapter->id,
                    'title' => 'Environment Setup',
                    'slug' => Str::slug('Environment Setup ' . $course->id),
                    'video_url' => 'https://www.youtube.com/watch?v=4pP-v4-qR_4',
                    'duration_minutes' => 15,
                    'order' => 2,
                    'is_preview' => false,
                    'content' => 'Now let\'s set up your development environment.'
                ]);
            }
        }
    }
}
