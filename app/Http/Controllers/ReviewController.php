<?php

namespace App\Http\Controllers;

use App\Models\Cohort;
use App\Models\CohortEnrollment;
use App\Models\DigitalProduct;
use App\Models\ProductPurchase;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function storeCohortReview(Request $request, Cohort $cohort)
    {
        $enrolled = CohortEnrollment::where('user_id', auth()->id())
            ->where('cohort_id', $cohort->id)
            ->exists();

        if (!$enrolled) {
            return back()->with('error', 'You must be enrolled in this cohort to leave a review.');
        }

        $existing = Review::where('user_id', auth()->id())
            ->where('reviewable_type', Cohort::class)
            ->where('reviewable_id', $cohort->id)
            ->exists();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this cohort.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'reviewable_type' => Cohort::class,
            'reviewable_id' => $cohort->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and is pending approval.');
    }

    public function storeProductReview(Request $request, DigitalProduct $product)
    {
        $purchased = ProductPurchase::where('user_id', auth()->id())
            ->where('digital_product_id', $product->id)
            ->exists();

        if (!$purchased) {
            return back()->with('error', 'You must purchase this product to leave a review.');
        }

        $existing = Review::where('user_id', auth()->id())
            ->where('reviewable_type', DigitalProduct::class)
            ->where('reviewable_id', $product->id)
            ->exists();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'reviewable_type' => DigitalProduct::class,
            'reviewable_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and is pending approval.');
    }
}
