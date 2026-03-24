<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MentorshipSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

    // Password Reset
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\ChapterController as AdminChapterController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;

/*
|--------------------------------------------------------------------------
| Authenticated Platform Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Email Verification
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard')->with('success', 'Email verified successfully!');
    })->middleware('signed')->name('verification.verify');
    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Routes
    Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Course Management
        Route::resource('courses', AdminCourseController::class);

        // Project Management
        Route::resource('projects', AdminProjectController::class);

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

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::post('/projects/{project}/enroll', [ProjectController::class, 'enroll'])->name('projects.enroll');

    // Mentorship
    Route::get('/mentorship', [MentorshipController::class, 'index'])->name('mentorship.index');
    Route::get('/mentorship/sessions', [MentorshipSessionController::class, 'index'])->name('mentorship.sessions.index');
    Route::get('/mentorship/{mentor}', [MentorshipController::class, 'show'])->name('mentorship.show');
    Route::post('/mentorship/{mentor}/book', [MentorshipSessionController::class, 'store'])->name('mentorship.book');
    Route::get('/mentorship/sessions/{session}/join', [MentorshipSessionController::class, 'join'])->name('mentorship.sessions.join');

});
