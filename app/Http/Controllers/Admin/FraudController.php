<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AffiliateClick;

class FraudController extends Controller
{
    public function index()
    {
        $suspiciousClicks = AffiliateClick::where('is_suspicious', true)
            ->with(['affiliateLink.user', 'user'])
            ->latest('created_at')
            ->paginate(25);

        $stats = [
            'total_suspicious' => AffiliateClick::where('is_suspicious', true)->count(),
            'today_suspicious' => AffiliateClick::where('is_suspicious', true)->whereDate('created_at', today())->count(),
            'self_clicks' => AffiliateClick::where('suspicious_reason', 'like', 'Self-click%')->count(),
            'rapid_clicks' => AffiliateClick::where('suspicious_reason', 'like', 'Rapid clicks%')->count(),
        ];

        return view('admin.fraud.index', compact('suspiciousClicks', 'stats'));
    }
}
