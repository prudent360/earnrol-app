<?php

namespace App\Services;

use App\Models\AffiliateClick;
use App\Models\AffiliateLink;
use App\Models\User;
use Illuminate\Http\Request;

class FraudDetectionService
{
    public static function logClick(AffiliateLink $link, Request $request): AffiliateClick
    {
        $ip = $request->ip();
        $userId = auth()->id();
        $isUnique = self::isUniqueClick($link, $ip);
        $isSuspicious = false;
        $reason = null;

        // Self-click detection
        if (self::isSelfClick($link, $userId)) {
            $isSuspicious = true;
            $reason = 'Self-click: affiliate clicked own link';
        }

        // Duplicate IP within 1 hour
        if (!$isSuspicious && self::isDuplicateIp($link, $ip, 60)) {
            $isSuspicious = true;
            $reason = 'Rapid clicks: same IP within 60 minutes';
        }

        // Excessive clicks from same IP (more than 10 in 24 hours)
        if (!$isSuspicious && self::isExcessiveClicks($link, $ip, 10, 1440)) {
            $isSuspicious = true;
            $reason = 'Excessive clicks: >10 from same IP in 24 hours';
        }

        return AffiliateClick::create([
            'affiliate_link_id' => $link->id,
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'user_id' => $userId,
            'is_unique' => $isUnique,
            'is_suspicious' => $isSuspicious,
            'suspicious_reason' => $reason,
        ]);
    }

    public static function isSelfClick(AffiliateLink $link, ?int $userId): bool
    {
        return $userId && $link->user_id === $userId;
    }

    public static function isDuplicateIp(AffiliateLink $link, string $ip, int $windowMinutes = 60): bool
    {
        return AffiliateClick::where('affiliate_link_id', $link->id)
            ->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes($windowMinutes))
            ->exists();
    }

    public static function isExcessiveClicks(AffiliateLink $link, string $ip, int $maxClicks, int $windowMinutes): bool
    {
        return AffiliateClick::where('affiliate_link_id', $link->id)
            ->where('ip_address', $ip)
            ->where('created_at', '>=', now()->subMinutes($windowMinutes))
            ->count() >= $maxClicks;
    }

    public static function isUniqueClick(AffiliateLink $link, string $ip): bool
    {
        return !AffiliateClick::where('affiliate_link_id', $link->id)
            ->where('ip_address', $ip)
            ->exists();
    }

    public static function hasSuspiciousActivity(int $affiliateLinkId, int $buyerUserId): bool
    {
        return AffiliateClick::where('affiliate_link_id', $affiliateLinkId)
            ->where('is_suspicious', true)
            ->where('user_id', $buyerUserId)
            ->exists();
    }
}
