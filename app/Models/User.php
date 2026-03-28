<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'phone',
        'bio',
        'avatar',
        'date_of_birth',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'referral_code',
        'referred_by',
        'wallet_balance',
        'bank_name',
        'bank_account_name',
        'bank_account_number',
        'bank_sort_code',
        'is_creator',
        'active_mode',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'wallet_balance' => 'decimal:2',
            'date_of_birth' => 'date',
            'is_creator' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['superadmin', 'admin']);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isStudent(): bool
    {
        return $this->role === 'learner' || $this->role === null || $this->role === '';
    }

    public function isCreator(): bool
    {
        return (bool) $this->is_creator;
    }

    public function inCreatorMode(): bool
    {
        return $this->isCreator() && $this->active_mode === 'creator';
    }

    public function creatorApplication(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CreatorApplication::class)->latest();
    }

    public function cohortEnrollments(): HasMany
    {
        return $this->hasMany(CohortEnrollment::class);
    }

    public function enrolledCohorts(): BelongsToMany
    {
        return $this->belongsToMany(Cohort::class, 'cohort_enrollments')
                    ->withPivot('payment_id', 'enrolled_at')
                    ->withTimestamps();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    public function referralEarnings(): HasMany
    {
        return $this->hasMany(ReferralEarning::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function productPurchases(): HasMany
    {
        return $this->hasMany(ProductPurchase::class);
    }

    public function digitalProducts(): HasMany
    {
        return $this->hasMany(DigitalProduct::class);
    }

    public function createdCohorts(): HasMany
    {
        return $this->hasMany(Cohort::class, 'creator_id');
    }

    public function creatorEarnings(): HasMany
    {
        return $this->hasMany(CreatorEarning::class, 'creator_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function affiliateLinks(): HasMany
    {
        return $this->hasMany(AffiliateLink::class);
    }

    public function affiliateSales(): HasMany
    {
        return $this->hasMany(AffiliateSale::class, 'affiliate_user_id');
    }

    public function coachingServices(): HasMany
    {
        return $this->hasMany(CoachingService::class);
    }

    public function coachingBookings(): HasMany
    {
        return $this->hasMany(CoachingBooking::class);
    }

    public function membershipPlans(): HasMany
    {
        return $this->hasMany(MembershipPlan::class);
    }

    public function membershipSubscriptions(): HasMany
    {
        return $this->hasMany(MembershipSubscription::class);
    }

    public function hasActiveSubscription(MembershipPlan $plan): bool
    {
        return $this->membershipSubscriptions()
            ->where('membership_plan_id', $plan->id)
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('current_period_end')
                  ->orWhere('current_period_end', '>', now());
            })
            ->exists();
    }

    public function generateReferralCode(): void
    {
        do {
            $code = Str::upper(Str::random(8));
        } while (static::where('referral_code', $code)->exists());

        $this->update(['referral_code' => $code]);
    }

    public static function generateUsername(string $name): string
    {
        $base = Str::slug($name);
        if (empty($base)) {
            $base = 'user';
        }
        $username = $base;
        $counter = 1;
        while (static::where('username', $username)->exists()) {
            $username = $base . '-' . $counter;
            $counter++;
        }
        return $username;
    }

    public function referralLink(): string
    {
        return url('/register?ref=' . $this->referral_code);
    }

    /**
     * Send the password reset notification using DB template.
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    /**
     * Send the email verification notification using DB template.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification());
    }
}
