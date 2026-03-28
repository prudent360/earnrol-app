<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use App\Models\Coupon;
use App\Models\DigitalProduct;
use App\Models\MembershipPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::where('creator_id', Auth::id())
            ->latest()
            ->paginate(15);

        return view('creator.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $cohorts = Cohort::where('creator_id', Auth::id())->orderBy('title')->get(['id', 'title']);
        $products = DigitalProduct::where('user_id', Auth::id())->orderBy('title')->get(['id', 'title']);
        $memberships = MembershipPlan::where('user_id', Auth::id())->orderBy('title')->get(['id', 'title']);

        return view('creator.coupons.create', compact('cohorts', 'products', 'memberships'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'           => 'required|string|max:50|unique:coupons,code',
            'description'    => 'nullable|string|max:255',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_purchase'   => 'nullable|numeric|min:0',
            'max_discount'   => 'nullable|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:1',
            'applies_to'     => 'required|in:cohort,product,membership',
            'applicable_id'  => 'required|integer',
            'starts_at'      => 'nullable|date',
            'expires_at'     => 'nullable|date|after_or_equal:starts_at',
            'is_active'      => 'boolean',
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');
        $data['creator_id'] = Auth::id();

        // Verify ownership of the target item
        if (!$this->verifyOwnership($data['applies_to'], $data['applicable_id'])) {
            return back()->with('error', 'You can only create coupons for your own items.');
        }

        Coupon::create($data);

        return redirect()->route('creator.coupons.index')
            ->with('success', 'Discount code created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $this->authorizeOwner($coupon);

        $cohorts = Cohort::where('creator_id', Auth::id())->orderBy('title')->get(['id', 'title']);
        $products = DigitalProduct::where('user_id', Auth::id())->orderBy('title')->get(['id', 'title']);
        $memberships = MembershipPlan::where('user_id', Auth::id())->orderBy('title')->get(['id', 'title']);

        return view('creator.coupons.edit', compact('coupon', 'cohorts', 'products', 'memberships'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $this->authorizeOwner($coupon);

        $data = $request->validate([
            'code'           => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description'    => 'nullable|string|max:255',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_purchase'   => 'nullable|numeric|min:0',
            'max_discount'   => 'nullable|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:1',
            'applies_to'     => 'required|in:cohort,product,membership',
            'applicable_id'  => 'required|integer',
            'starts_at'      => 'nullable|date',
            'expires_at'     => 'nullable|date|after_or_equal:starts_at',
            'is_active'      => 'boolean',
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');

        if (!$this->verifyOwnership($data['applies_to'], $data['applicable_id'])) {
            return back()->with('error', 'You can only create coupons for your own items.');
        }

        $coupon->update($data);

        return redirect()->route('creator.coupons.index')
            ->with('success', 'Discount code updated.');
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorizeOwner($coupon);
        $coupon->delete();

        return redirect()->route('creator.coupons.index')
            ->with('success', 'Discount code deleted.');
    }

    protected function authorizeOwner(Coupon $coupon): void
    {
        if ($coupon->creator_id !== Auth::id()) {
            abort(403, 'You do not own this discount code.');
        }
    }

    private function verifyOwnership(string $type, int $itemId): bool
    {
        return match ($type) {
            'cohort' => Cohort::where('id', $itemId)->where('creator_id', Auth::id())->exists(),
            'product' => DigitalProduct::where('id', $itemId)->where('user_id', Auth::id())->exists(),
            'membership' => MembershipPlan::where('id', $itemId)->where('user_id', Auth::id())->exists(),
            default => false,
        };
    }
}
