<?php

namespace App\Http\Controllers;

use App\Models\DigitalProduct;
use App\Models\ProductPurchase;
use App\Models\Review;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class DigitalProductController extends Controller
{
    public function index()
    {
        $products = DigitalProduct::published()
            ->latest()
            ->paginate(12);

        $currencySymbol = Setting::get('currency_symbol', '£');

        return view('products.index', compact('products', 'currencySymbol'));
    }

    public function show(DigitalProduct $product)
    {
        if ($product->status !== 'published') {
            abort(404);
        }

        $purchased = auth()->check()
            ? ProductPurchase::where('user_id', auth()->id())
                ->where('digital_product_id', $product->id)
                ->exists()
            : false;

        $currencySymbol = Setting::get('currency_symbol', '£');
        $stripeEnabled = Setting::get('stripe_enabled') === '1';
        $paypalEnabled = Setting::get('paypal_enabled') === '1';
        $bankTransferEnabled = Setting::get('bank_transfer_enabled') === '1';
        $paymentEnabled = $stripeEnabled || $paypalEnabled || $bankTransferEnabled;

        $reviews = $product->approvedReviews()->with('user')->latest()->get();
        $averageRating = $product->averageRating();
        $reviewCount = $reviews->count();
        $userReview = auth()->check()
            ? $product->reviews()->where('user_id', auth()->id())->first()
            : null;

        return view('products.show', compact(
            'product', 'purchased', 'currencySymbol',
            'stripeEnabled', 'paypalEnabled', 'bankTransferEnabled', 'paymentEnabled',
            'reviews', 'averageRating', 'reviewCount', 'userReview'
        ));
    }

    public function myDownloads()
    {
        $purchases = ProductPurchase::where('user_id', auth()->id())
            ->with('product')
            ->latest('purchased_at')
            ->paginate(12);

        $currencySymbol = Setting::get('currency_symbol', '£');

        return view('products.downloads', compact('purchases', 'currencySymbol'));
    }

    public function download(DigitalProduct $product)
    {
        $purchase = ProductPurchase::where('user_id', auth()->id())
            ->where('digital_product_id', $product->id)
            ->firstOrFail();

        $purchase->increment('download_count');
        $product->increment('download_count');

        return Storage::download($product->file_path, $product->file_name);
    }

    public function getFree(DigitalProduct $product)
    {
        if ($product->status !== 'published' || !$product->isFree()) {
            abort(404);
        }

        ProductPurchase::firstOrCreate([
            'user_id' => auth()->id(),
            'digital_product_id' => $product->id,
        ], [
            'purchased_at' => now(),
        ]);

        return redirect()->route('products.show', $product)
            ->with('success', 'Product added to your downloads!');
    }
}
