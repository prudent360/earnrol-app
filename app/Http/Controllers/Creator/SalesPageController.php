<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\CoachingService;
use App\Models\Cohort;
use App\Models\DigitalProduct;
use App\Models\MembershipPlan;
use App\Models\SalesPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesPageController extends Controller
{
    public function index()
    {
        $salesPages = Auth::user()->salesPages()
            ->with('pageable')
            ->latest()
            ->paginate(20);

        return view('creator.sales-pages.index', compact('salesPages'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        $products = $user->digitalProducts()->approved()->get();
        $cohorts = $user->createdCohorts()->where('approval_status', 'approved')->get();
        $memberships = $user->membershipPlans()->where('approval_status', 'approved')->get();
        $coaching = $user->coachingServices()->where('approval_status', 'approved')->get();

        $templates = SalesPage::TEMPLATES;

        return view('creator.sales-pages.create', compact('products', 'cohorts', 'memberships', 'coaching', 'templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pageable_type' => 'required|in:product,cohort,membership,coaching',
            'pageable_id' => 'required|integer',
            'template' => 'required|in:' . implode(',', array_keys(SalesPage::TEMPLATES)),
            'content' => 'nullable|array',
            'is_published' => 'boolean',
        ]);

        $typeMap = [
            'product' => DigitalProduct::class,
            'cohort' => Cohort::class,
            'membership' => MembershipPlan::class,
            'coaching' => CoachingService::class,
        ];

        $pageableType = $typeMap[$data['pageable_type']];
        $pageable = $pageableType::findOrFail($data['pageable_id']);

        // Ensure creator owns this item
        $creatorField = $pageableType === Cohort::class ? 'creator_id' : 'user_id';
        if ($pageable->$creatorField !== Auth::id()) {
            return back()->with('error', 'You can only create sales pages for your own items.');
        }

        // Check no existing sales page
        if ($pageable->salesPage) {
            return back()->with('error', 'This item already has a sales page.');
        }

        SalesPage::create([
            'user_id' => Auth::id(),
            'pageable_type' => $pageableType,
            'pageable_id' => $data['pageable_id'],
            'template' => $data['template'],
            'content' => $data['content'] ?? [],
            'is_published' => $request->boolean('is_published'),
            'custom_slug' => SalesPage::generateSlug($pageable->title),
        ]);

        return redirect()->route('creator.sales-pages.index')->with('success', 'Sales page created!');
    }

    public function edit(SalesPage $salesPage)
    {
        if ($salesPage->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        $templates = SalesPage::TEMPLATES;

        return view('creator.sales-pages.edit', compact('salesPage', 'templates'));
    }

    public function update(Request $request, SalesPage $salesPage)
    {
        if ($salesPage->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        $data = $request->validate([
            'template' => 'required|in:' . implode(',', array_keys(SalesPage::TEMPLATES)),
            'content' => 'nullable|array',
            'is_published' => 'boolean',
        ]);

        $salesPage->update([
            'template' => $data['template'],
            'content' => $data['content'] ?? [],
            'is_published' => $request->boolean('is_published'),
        ]);

        return back()->with('success', 'Sales page updated!');
    }

    public function destroy(SalesPage $salesPage)
    {
        if ($salesPage->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        $salesPage->delete();

        return redirect()->route('creator.sales-pages.index')->with('success', 'Sales page deleted.');
    }

    public function preview(SalesPage $salesPage)
    {
        if ($salesPage->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized.');
        }

        $content = $salesPage->content ?? [];
        $pageable = $salesPage->pageable;
        $creator = $salesPage->user;

        return view('sales-pages.templates.' . $salesPage->template, compact('salesPage', 'content', 'pageable', 'creator'));
    }
}
