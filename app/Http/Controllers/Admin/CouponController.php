<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cohort;
use App\Models\Coupon;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $cohorts  = Cohort::orderBy('title')->get(['id', 'title']);
        $products = DigitalProduct::orderBy('title')->get(['id', 'title']);
        return view('admin.coupons.create', compact('cohorts', 'products'));
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
            'applies_to'     => 'required|in:all,cohort,product',
            'applicable_id'  => 'nullable|integer',
            'starts_at'      => 'nullable|date',
            'expires_at'     => 'nullable|date|after_or_equal:starts_at',
            'is_active'      => 'boolean',
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');

        if ($data['applies_to'] === 'all') {
            $data['applicable_id'] = null;
        }

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $cohorts  = Cohort::orderBy('title')->get(['id', 'title']);
        $products = DigitalProduct::orderBy('title')->get(['id', 'title']);
        return view('admin.coupons.edit', compact('coupon', 'cohorts', 'products'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code'           => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'description'    => 'nullable|string|max:255',
            'discount_type'  => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'min_purchase'   => 'nullable|numeric|min:0',
            'max_discount'   => 'nullable|numeric|min:0',
            'usage_limit'    => 'nullable|integer|min:1',
            'applies_to'     => 'required|in:all,cohort,product',
            'applicable_id'  => 'nullable|integer',
            'starts_at'      => 'nullable|date',
            'expires_at'     => 'nullable|date|after_or_equal:starts_at',
            'is_active'      => 'boolean',
        ]);

        $data['code'] = strtoupper(trim($data['code']));
        $data['is_active'] = $request->boolean('is_active');

        if ($data['applies_to'] === 'all') {
            $data['applicable_id'] = null;
        }

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
