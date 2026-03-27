<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CohortController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentHistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordResetController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use App\Http\Controllers\CohortMaterialController;
use App\Http\Controllers\CohortDiscussionController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\CohortController as AdminCohortController;
use App\Http\Controllers\Admin\CohortMaterialController as AdminCohortMaterialController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\WithdrawalController as AdminWithdrawalController;
use App\Http\Controllers\DigitalProductController;
use App\Http\Controllers\ProductPaymentController;
use App\Http\Controllers\Admin\DigitalProductController as AdminDigitalProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateVerificationController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\CertificateController as AdminCertificateController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\CreatorToggleController;
use App\Http\Controllers\CreatorProfileController;
use App\Http\Controllers\Creator\DashboardController as CreatorDashboardController;
use App\Http\Controllers\Creator\ProductController as CreatorProductController;
use App\Http\Controllers\Creator\CohortController as CreatorCohortController;
use App\Http\Controllers\Creator\EarningController as CreatorEarningController;

/*
|--------------------------------------------------------------------------
| Public / Marketing Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

// PWA
Route::get('/manifest.webmanifest', function () {
    $appName = \App\Models\Setting::get('app_name', 'EarnRol');
    $brandColor = \App\Models\Setting::get('brand_color', '#e05a3a');

    return response()->json([
        'name' => $appName,
        'short_name' => $appName,
        'description' => \App\Models\Setting::get('meta_description', 'Live tech training cohorts with expert instructors.'),
        'start_url' => '/',
        'display' => 'standalone',
        'background_color' => '#f5f6fa',
        'theme_color' => $brandColor,
        'orientation' => 'portrait-primary',
        'icons' => [
            ['src' => '/icons/icon.svg', 'sizes' => 'any', 'type' => 'image/svg+xml', 'purpose' => 'any'],
            ['src' => '/icons/icon-192.png', 'sizes' => '192x192', 'type' => 'image/png'],
            ['src' => '/icons/icon-512.png', 'sizes' => '512x512', 'type' => 'image/png'],
        ],
    ])->header('Content-Type', 'application/manifest+json');
})->name('manifest');

Route::get('/offline', function () {
    return view('offline');
});

// Public Certificate Verification
Route::get('/verify/{certificate_number}', [CertificateVerificationController::class, 'verify'])->name('certificates.verify');

// Creator Public Storefront
Route::get('/c/{username}', [CreatorProfileController::class, 'show'])->name('creator.profile');

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

/*
|--------------------------------------------------------------------------
| Authenticated Routes
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

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cohorts
    Route::get('/my-classes', [CohortController::class, 'index'])->name('cohorts.index');

    Route::post('/cohorts/{cohort}/enrol', [CohortController::class, 'enrollFree'])->name('cohorts.enrol-free');

    // Cohort Materials (student view)
    Route::get('/cohorts/{cohort}/materials', [CohortMaterialController::class, 'show'])->name('cohorts.materials');
    Route::post('/cohorts/{cohort}/assignments/{material}/submit', [CohortMaterialController::class, 'submit'])->name('cohorts.submit');

    // Cohort Discussions
    Route::get('/cohorts/{cohort}/discussions', [CohortDiscussionController::class, 'index'])->name('cohorts.discussions');
    Route::post('/cohorts/{cohort}/discussions', [CohortDiscussionController::class, 'store'])->name('cohorts.discussions.store');
    Route::delete('/cohorts/{cohort}/discussions/{discussion}', [CohortDiscussionController::class, 'destroy'])->name('cohorts.discussions.destroy');

    // Reviews
    Route::post('/cohorts/{cohort}/reviews', [ReviewController::class, 'storeCohortReview'])->name('cohorts.reviews.store');
    Route::post('/products/{product}/reviews', [ReviewController::class, 'storeProductReview'])->name('products.reviews.store');

    // Certificates
    Route::get('/my-certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/{certificate:certificate_number}/download', [CertificateController::class, 'download'])->name('certificates.download');

    // Coupon Validation (AJAX)
    Route::post('/coupons/validate', [CouponController::class, 'validate'])->name('coupons.validate');

    // Payment History
    Route::get('/my-payments', [PaymentHistoryController::class, 'index'])->name('payments.history');
    Route::get('/my-payments/export', [PaymentHistoryController::class, 'exportCsv'])->name('payments.history.export');

    // Referrals
    Route::get('/referrals', [ReferralController::class, 'index'])->name('referrals.index');
    Route::put('/referrals/bank-details', [ReferralController::class, 'updateBankDetails'])->name('referrals.bank-details');
    Route::post('/referrals/withdraw', [ReferralController::class, 'requestWithdrawal'])->name('referrals.withdraw');

    // Digital Products
    Route::get('/products', [DigitalProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [DigitalProductController::class, 'show'])->name('products.show');
    Route::get('/my-downloads', [DigitalProductController::class, 'myDownloads'])->name('products.downloads');
    Route::get('/products/{product}/download', [DigitalProductController::class, 'download'])->name('products.download');
    Route::post('/products/{product}/get-free', [DigitalProductController::class, 'getFree'])->name('products.get-free');

    // Product Payments — Stripe
    Route::post('/products/{product}/checkout/stripe', [ProductPaymentController::class, 'stripeCheckout'])->name('products.stripe.checkout');
    Route::get('/products/stripe/callback', [ProductPaymentController::class, 'stripeCallback'])->name('products.stripe.callback');

    // Product Payments — PayPal
    Route::post('/products/{product}/checkout/paypal', [ProductPaymentController::class, 'paypalCheckout'])->name('products.paypal.checkout');
    Route::get('/products/paypal/callback', [ProductPaymentController::class, 'paypalCallback'])->name('products.paypal.callback');

    // Product Payments — Bank Transfer
    Route::get('/products/{product}/bank-transfer', [ProductPaymentController::class, 'bankTransferForm'])->name('products.bank-transfer');
    Route::post('/products/{product}/bank-transfer', [ProductPaymentController::class, 'bankTransferSubmit'])->name('products.bank-transfer.submit');

    // Payments — Stripe
    Route::post('/cohorts/{cohort}/checkout/stripe', [PaymentController::class, 'stripeCheckout'])->name('payments.stripe.checkout');
    Route::get('/payments/stripe/callback', [PaymentController::class, 'stripeCallback'])->name('payments.callback');

    // Payments — PayPal
    Route::post('/cohorts/{cohort}/checkout/paypal', [PaymentController::class, 'paypalCheckout'])->name('payments.paypal.checkout');
    Route::get('/payments/paypal/callback', [PaymentController::class, 'paypalCallback'])->name('payments.paypal.callback');

    // Payments — Bank Transfer
    Route::get('/cohorts/{cohort}/bank-transfer', [PaymentController::class, 'bankTransferForm'])->name('payments.bank-transfer');
    Route::post('/cohorts/{cohort}/bank-transfer', [PaymentController::class, 'bankTransferSubmit'])->name('payments.bank-transfer.submit');

    // Creator Application
    Route::get('/become-creator', [CreatorToggleController::class, 'showApplicationForm'])->name('creator.apply');
    Route::post('/become-creator', [CreatorToggleController::class, 'apply'])->name('creator.apply.submit');
    Route::post('/creator/switch-mode', [CreatorToggleController::class, 'switchMode'])->name('creator.switch-mode');

    // Creator Routes
    Route::middleware(\App\Http\Middleware\CreatorMiddleware::class)->prefix('creator')->name('creator.')->group(function () {
        Route::get('/dashboard', [CreatorDashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', CreatorProductController::class);
        Route::resource('cohorts', CreatorCohortController::class);
        Route::get('/earnings', [CreatorEarningController::class, 'index'])->name('earnings.index');
    });

    // Stop Impersonation (outside admin middleware — impersonated user isn't admin)
    Route::post('/impersonate/stop', [AdminUserController::class, 'stopImpersonating'])->name('impersonate.stop');

    // Admin Routes
    Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
        // Creator Applications
        Route::get('/creator-applications', [\App\Http\Controllers\Admin\CreatorApplicationController::class, 'index'])->name('creator-applications.index');
        Route::post('/creator-applications/{application}/approve', [\App\Http\Controllers\Admin\CreatorApplicationController::class, 'approve'])->name('creator-applications.approve');
        Route::post('/creator-applications/{application}/reject', [\App\Http\Controllers\Admin\CreatorApplicationController::class, 'reject'])->name('creator-applications.reject');

        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/credit-wallet', [AdminUserController::class, 'creditWallet'])->name('users.credit-wallet');
        Route::post('/users/{user}/impersonate', [AdminUserController::class, 'impersonate'])->name('users.impersonate');

        // Cohort Management
        Route::resource('cohorts', AdminCohortController::class);

        // Digital Product Management
        Route::resource('products', AdminDigitalProductController::class)->except(['show']);
        Route::post('/products/{product}/approve', [AdminDigitalProductController::class, 'approve'])->name('products.approve');
        Route::post('/products/{product}/reject', [AdminDigitalProductController::class, 'reject'])->name('products.reject');

        Route::post('/cohorts/{cohort}/approve', [AdminCohortController::class, 'approve'])->name('cohorts.approve');
        Route::post('/cohorts/{cohort}/reject', [AdminCohortController::class, 'reject'])->name('cohorts.reject');

        // Cohort Materials (admin)
        Route::get('/cohorts/{cohort}/materials', [AdminCohortMaterialController::class, 'index'])->name('cohorts.materials.index');
        Route::get('/cohorts/{cohort}/materials/create', [AdminCohortMaterialController::class, 'create'])->name('cohorts.materials.create');
        Route::post('/cohorts/{cohort}/materials', [AdminCohortMaterialController::class, 'store'])->name('cohorts.materials.store');
        Route::delete('/cohorts/{cohort}/materials/{material}', [AdminCohortMaterialController::class, 'destroy'])->name('cohorts.materials.destroy');
        Route::get('/cohorts/{cohort}/materials/{material}/submissions', [AdminCohortMaterialController::class, 'submissions'])->name('cohorts.materials.submissions');
        Route::put('/cohorts/{cohort}/submissions/{submission}/grade', [AdminCohortMaterialController::class, 'grade'])->name('cohorts.submissions.grade');

        // Payments
        Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::post('/payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
        Route::post('/payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');

        // Withdrawals
        Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::post('/withdrawals/{withdrawal}/approve', [AdminWithdrawalController::class, 'approve'])->name('withdrawals.approve');
        Route::post('/withdrawals/{withdrawal}/reject', [AdminWithdrawalController::class, 'reject'])->name('withdrawals.reject');

        // Certificates
        Route::get('/cohorts/{cohort}/certificates', [AdminCertificateController::class, 'index'])->name('cohorts.certificates.index');
        Route::post('/cohorts/{cohort}/certificates/issue', [AdminCertificateController::class, 'issue'])->name('cohorts.certificates.issue');

        // Reviews Moderation
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // Coupons
        Route::resource('coupons', AdminCouponController::class)->except(['show']);

        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // Settings
        Route::post('/settings/test-email', [SettingsController::class, 'sendTestEmail'])->name('settings.test-email');
        Route::post('/settings/templates/toggle', [SettingsController::class, 'toggleTemplate'])->name('settings.templates.toggle');
        Route::post('/settings/templates/preview', [SettingsController::class, 'previewTemplate'])->name('settings.templates.preview');
        Route::get('/settings/{tab?}', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/{tab}', [SettingsController::class, 'update'])->name('settings.update');
    });
});
