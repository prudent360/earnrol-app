<?php

namespace App\Http\Controllers;

use App\Models\SalesPage;

class SalesPageController extends Controller
{
    public function show(string $slug)
    {
        $salesPage = SalesPage::where('custom_slug', $slug)
            ->where('is_published', true)
            ->with(['pageable', 'user'])
            ->firstOrFail();

        $content = $salesPage->content ?? [];
        $pageable = $salesPage->pageable;
        $creator = $salesPage->user;

        return view('sales-pages.templates.' . $salesPage->template, compact('salesPage', 'content', 'pageable', 'creator'));
    }
}
