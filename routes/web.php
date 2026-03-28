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
use App\Http\Controllers\AffiliateTrackingController;
use App\Http\Controllers\AffiliateDashboardController;
use App\Http\Controllers\Creator\AffiliateController as CreatorAffiliateController;
use App\Http\Controllers\Admin\AffiliateController as AdminAffiliateController;
use App\Http\Controllers\Creator\CoachingController as CreatorCoachingController;
use App\Http\Controllers\Creator\CouponController as CreatorCouponController;
use App\Http\Controllers\CoachingController;
use App\Http\Controllers\CoachingPaymentController;
use App\Http\Controllers\Admin\CoachingController as AdminCoachingController;
use App\Http\Controllers\Creator\EarningController as CreatorEarningController;
use App\Http\Controllers\Creator\MembershipController as CreatorMembershipController;
use App\Http\Controllers\Creator\MembershipContentController as CreatorMembershipContentController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\MembershipPaymentController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\Admin\MembershipController as AdminMembershipController;
use App\Http\Controllers\UserReportController;

/*
|--------------------------------------------------------------------------
| Public / Marketing Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');


// Public Certificate Verification
Route::get('/verify/{certificate_number}', [CertificateVerificationController::class, 'verify'])->name('certificates.verify');

// Creator Public Storefront
Route::get('/c/{username}', [CreatorProfileController::class, 'show'])->name('creator.profile');

// Affiliate Tracking (no auth)
Route::get('/ref/{code}', [AffiliateTrackingController::class, 'track'])->name('affiliate.track');

// Stripe Webhook (no auth)
Route::post('/webhooks/stripe', [StripeWebhookController::class, 'handle'])->name('webhooks.stripe');

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

    // User Report
    Route::get('/my-report', [UserReportController::class, 'index'])->name('user.report');

    // Memberships
    Route::get('/memberships', [MembershipController::class, 'index'])->name('memberships.index');
    Route::get('/memberships/mine', [MembershipController::class, 'myMemberships'])->name('memberships.mine');
    Route::get('/memberships/{membership:slug}', [MembershipController::class, 'show'])->name('memberships.show');
    Route::get('/memberships/{membership:slug}/content', [MembershipController::class, 'content'])->name('memberships.content');
    Route::post('/memberships/{membership:slug}/cancel', [MembershipController::class, 'cancel'])->name('memberships.cancel');

    // Membership Payments — Stripe
    Route::post('/memberships/{membership:slug}/checkout/stripe', [MembershipPaymentController::class, 'stripeCheckout'])->name('memberships.stripe.checkout');
    Route::get('/memberships/stripe/callback', [MembershipPaymentController::class, 'stripeCallback'])->name('memberships.stripe.callback');

    // Membership Payments — Bank Transfer
    Route::get('/memberships/{membership:slug}/bank-transfer', [MembershipPaymentController::class, 'bankTransferForm'])->name('memberships.bank-transfer');
    Route::post('/memberships/{membership:slug}/bank-transfer', [MembershipPaymentController::class, 'bankTransferSubmit'])->name('memberships.bank-transfer.submit');

    // Coaching
    Route::get('/coaching', [CoachingController::class, 'index'])->name('coaching.index');
    Route::get('/coaching/my-sessions', [CoachingController::class, 'myBookings'])->name('coaching.my-bookings');
    Route::get('/coaching/{coaching:slug}', [CoachingController::class, 'show'])->name('coaching.show');

    // Coaching Payments — Stripe
    Route::post('/coaching/{coaching:slug}/checkout/stripe', [CoachingPaymentController::class, 'stripeCheckout'])->name('coaching.stripe.checkout');
    Route::get('/coaching/stripe/callback', [CoachingPaymentController::class, 'stripeCallback'])->name('coaching.stripe.callback');

    // Coaching Payments — Bank Transfer
    Route::get('/coaching/{coaching:slug}/bank-transfer', [CoachingPaymentController::class, 'bankTransferForm'])->name('coaching.bank-transfer');
    Route::post('/coaching/{coaching:slug}/bank-transfer', [CoachingPaymentController::class, 'bankTransferSubmit'])->name('coaching.bank-transfer.submit');

    // Affiliate Dashboard
    Route::prefix('affiliate')->name('affiliate.')->group(function () {
        Route::get('/dashboard', [AffiliateDashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [AffiliateDashboardController::class, 'products'])->name('products');
        Route::post('/generate-link', [AffiliateDashboardController::class, 'generateLink'])->name('generate-link');
        Route::get('/my-links', [AffiliateDashboardController::class, 'myLinks'])->name('links');
        Route::get('/earnings', [AffiliateDashboardController::class, 'earnings'])->name('earnings');
    });

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
        Route::resource('memberships', CreatorMembershipController::class);
        Route::resource('coupons', CreatorCouponController::class);
        Route::get('/memberships/{membership:slug}/subscribers', [CreatorMembershipController::class, 'subscribers'])->name('memberships.subscribers');
        Route::get('/memberships/{membership:slug}/contents', [CreatorMembershipContentController::class, 'index'])->name('memberships.contents.index');
        Route::get('/memberships/{membership:slug}/contents/create', [CreatorMembershipContentController::class, 'create'])->name('memberships.contents.create');
        Route::post('/memberships/{membership:slug}/contents', [CreatorMembershipContentController::class, 'store'])->name('memberships.contents.store');
        Route::delete('/memberships/{membership:slug}/contents/{content}', [CreatorMembershipContentController::class, 'destroy'])->name('memberships.contents.destroy');
        Route::resource('coaching', CreatorCoachingController::class);
        Route::get('/coaching/{coaching:slug}/slots', [CreatorCoachingController::class, 'slots'])->name('coaching.slots.index');
        Route::get('/coaching/{coaching:slug}/slots/create', [CreatorCoachingController::class, 'createSlot'])->name('coaching.slots.create');
        Route::post('/coaching/{coaching:slug}/slots', [CreatorCoachingController::class, 'storeSlot'])->name('coaching.slots.store');
        Route::delete('/coaching/{coaching:slug}/slots/{slot}', [CreatorCoachingController::class, 'destroySlot'])->name('coaching.slots.destroy');
        Route::get('/coaching/{coaching:slug}/bookings', [CreatorCoachingController::class, 'bookings'])->name('coaching.bookings');
        Route::put('/coaching/bookings/{booking}/meeting-link', [CreatorCoachingController::class, 'updateMeetingLink'])->name('coaching.bookings.meeting-link');
        Route::post('/coaching/bookings/{booking}/complete', [CreatorCoachingController::class, 'markCompleted'])->name('coaching.bookings.complete');
        Route::get('/earnings', [CreatorEarningController::class, 'index'])->name('earnings.index');
        Route::get('/affiliate-sales', [CreatorAffiliateController::class, 'index'])->name('affiliate-sales.index');
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

        // Affiliates
        Route::get('/affiliates', [AdminAffiliateController::class, 'index'])->name('affiliates.index');

        // Coaching
        Route::get('/coaching', [AdminCoachingController::class, 'index'])->name('coaching.index');
        Route::post('/coaching/{coaching:slug}/approve', [AdminCoachingController::class, 'approve'])->name('coaching.approve');
        Route::post('/coaching/{coaching:slug}/reject', [AdminCoachingController::class, 'reject'])->name('coaching.reject');

        // Memberships
        Route::get('/memberships', [AdminMembershipController::class, 'index'])->name('memberships.index');
        Route::get('/memberships/{membership:slug}', [AdminMembershipController::class, 'show'])->name('memberships.show');
        Route::post('/memberships/{membership:slug}/approve', [AdminMembershipController::class, 'approve'])->name('memberships.approve');
        Route::post('/memberships/{membership:slug}/reject', [AdminMembershipController::class, 'reject'])->name('memberships.reject');

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
