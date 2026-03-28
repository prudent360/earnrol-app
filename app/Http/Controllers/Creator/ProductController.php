<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Auth::user()->digitalProducts()
            ->latest()
            ->paginate(10);

        return view('creator.products.index', compact('products'));
    }

    public function create()
    {
        return view('creator.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'document_type' => 'required|in:' . implode(',', array_keys(DigitalProduct::DOCUMENT_TYPES)),
            'cover_image'   => 'nullable|image|max:4096',
            'file'          => 'required|file|max:51200',
        ]);

        $data = $request->only(['title', 'description', 'price', 'document_type']);
        $data['user_id'] = Auth::id();
        $data['slug'] = DigitalProduct::generateSlug($data['title']);
        $data['status'] = 'published';
        $data['approval_status'] = 'pending';

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('products/covers', 'public');
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['file_path'] = $file->store('products/files');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $product = DigitalProduct::create($data);

        if (\App\Models\Setting::get('affiliate_enabled')) {
            $product->affiliateProduct()->updateOrCreate([], [
                'affiliate_enabled' => $request->boolean('affiliate_enabled'),
                'commission_percentage' => $request->input('affiliate_commission', 0),
            ]);
        }

        return redirect()->route('creator.products.index')
            ->with('success', 'Product submitted for review! It will be visible once approved by an admin.');
    }

    public function edit(DigitalProduct $product)
    {
        $this->authorizeOwner($product);

        return view('creator.products.edit', compact('product'));
    }

    public function update(Request $request, DigitalProduct $product)
    {
        $this->authorizeOwner($product);

        $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'document_type' => 'required|in:' . implode(',', array_keys(DigitalProduct::DOCUMENT_TYPES)),
            'cover_image'   => 'nullable|image|max:4096',
            'file'          => 'nullable|file|max:51200',
        ]);

        $data = $request->only(['title', 'description', 'price', 'document_type']);

        // Re-review if previously approved
        if ($product->approval_status === 'approved') {
            $data['approval_status'] = 'pending';
            $data['rejection_reason'] = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($product->cover_image) {
                Storage::disk('public')->delete($product->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('products/covers', 'public');
        }

        if ($request->hasFile('file')) {
            if ($product->file_path) {
                Storage::delete($product->file_path);
            }
            $file = $request->file('file');
            $data['file_path'] = $file->store('products/files');
            $data['file_name'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $product->update($data);

        if (\App\Models\Setting::get('affiliate_enabled')) {
            $product->affiliateProduct()->updateOrCreate([], [
                'affiliate_enabled' => $request->boolean('affiliate_enabled'),
                'commission_percentage' => $request->input('affiliate_commission', 0),
            ]);
        }

        return redirect()->route('creator.products.index')
            ->with('success', 'Product updated.' . ($product->approval_status === 'pending' ? ' It will be re-reviewed by an admin.' : ''));
    }

    public function destroy(DigitalProduct $product)
    {
        $this->authorizeOwner($product);

        if ($product->purchases()->exists()) {
            return back()->with('error', 'Cannot delete a product that has been purchased.');
        }

        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }
        if ($product->file_path) {
            Storage::delete($product->file_path);
        }

        $product->delete();

        return redirect()->route('creator.products.index')
            ->with('success', 'Product deleted.');
    }

    protected function authorizeOwner(DigitalProduct $product): void
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'You do not own this product.');
        }
    }
}
