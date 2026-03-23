<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| Public / Marketing Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\ChapterController as AdminChapterController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;

/*
|--------------------------------------------------------------------------
| Authenticated Platform Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware('role:superadmin')->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Course Management
        Route::get('/courses', [AdminCourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [AdminCourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [AdminCourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [AdminCourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy'])->name('courses.destroy');

        // Curriculum Management (AJAX)
        Route::post('/courses/{course}/chapters', [AdminChapterController::class, 'store'])->name('chapters.store');
        Route::put('/chapters/{chapter}', [AdminChapterController::class, 'update'])->name('chapters.update');
        Route::delete('/chapters/{chapter}', [AdminChapterController::class, 'destroy'])->name('chapters.destroy');

        Route::post('/chapters/{chapter}/lessons', [AdminLessonController::class, 'store'])->name('lessons.store');
        Route::put('/lessons/{lesson}', [AdminLessonController::class, 'update'])->name('lessons.update');
        Route::delete('/lessons/{lesson}', [AdminLessonController::class, 'destroy'])->name('lessons.destroy');

        // Settings — test-email must be declared before {tab} to avoid wildcard conflict
        Route::post('/settings/test-email', [SettingsController::class, 'sendTestEmail'])->name('settings.test-email');
        Route::get('/settings/{tab?}', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/{tab}', [SettingsController::class, 'update'])->name('settings.update');
    });

    // Learning / Courses
    Route::get('/learning', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/learning/{course}', [CourseController::class, 'show'])->name('courses.show');
    
    // Enrollment & Payments
    Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll'])->name('courses.enroll');
    Route::post('/courses/{course}/checkout', [PaymentController::class, 'initialize'])->name('payments.checkout');
    Route::get('/payments/callback', [PaymentController::class, 'callback'])->name('payments.callback');
    
    // Lesson Player
    Route::get('/learning/{course}/lessons/{lesson:slug}', [LessonController::class, 'show'])->name('courses.lessons.show');
    Route::post('/learning/{course}/lessons/{lesson:slug}/complete', [LessonController::class, 'complete'])->name('courses.lessons.complete');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');

    // Mentorship
    Route::get('/mentorship', [MentorshipController::class, 'index'])->name('mentorship.index');
    Route::get('/mentorship/{mentor}', [MentorshipController::class, 'show'])->name('mentorship.show');

    // Jobs
    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{job}/apply', [JobController::class, 'apply'])->name('jobs.apply');
});
