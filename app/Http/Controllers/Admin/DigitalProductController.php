<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DigitalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DigitalProductController extends Controller
{
    public function index()
    {
        $products = DigitalProduct::withCount('purchases')
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|max:4096',
            'file'        => 'required|file|max:51200',
        ]);

        $data = $request->only(['title', 'description', 'price', 'status']);
        $data['user_id'] = auth()->id();
        $data['slug'] = DigitalProduct::generateSlug($request->title);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('products/covers', 'public');
        }

        $file = $request->file('file');
        $data['file_path'] = $file->store('products/files');
        $data['file_name'] = $file->getClientOriginalName();
        $data['file_size'] = $file->getSize();

        DigitalProduct::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(DigitalProduct $product)
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(DigitalProduct $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, DigitalProduct $product)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'status'      => 'required|in:draft,published,archived',
            'cover_image' => 'nullable|image|max:4096',
            'file'        => 'nullable|file|max:51200',
        ]);

        $data = $request->only(['title', 'description', 'price', 'status']);

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

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(DigitalProduct $product)
    {
        if ($product->purchases()->count() > 0) {
            return back()->with('error', 'Cannot delete a product that has been purchased.');
        }

        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }
        if ($product->file_path) {
            Storage::delete($product->file_path);
        }

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
}
