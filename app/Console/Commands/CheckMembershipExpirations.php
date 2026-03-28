<?php

namespace App\Console\Commands;

use App\Models\MembershipSubscription;
use Illuminate\Console\Command;

class CheckMembershipExpirations extends Command
{
    protected $signature = 'memberships:check-expirations';
    protected $description = 'Expire memberships past their period end and send reminders';

    public function handle(): int
    {
        // Expire subscriptions past their end date
        $expired = MembershipSubscription::where('status', 'active')
            ->where(function ($q) {
                $q->whereNotNull('ends_at')->where('ends_at', '<=', now());
            })
            ->orWhere(function ($q) {
                $q->where('status', 'active')
                  ->whereNotNull('current_period_end')
                  ->where('current_period_end', '<=', now())
                  ->where('gateway', '!=', 'stripe'); // Stripe handles its own via webhooks
            })
            ->update(['status' => 'expired']);

        $this->info("Expired {$expired} subscriptions.");

        return self::SUCCESS;
    }
}
