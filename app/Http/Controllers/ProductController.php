<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    // 🔹 Sync Products from FakeStore API
    public function sync()
    {
        try {
            $response = Http::timeout(10)->get('https://fakestoreapi.com/products');

            if (! $response->successful()) {
                Log::warning('Products sync failed: remote API returned non-success', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return redirect()->route('products.index')->with('error', 'Failed to sync products (remote API error).');
            }

            $data = $response->json();

            if (! is_array($data)) {
                Log::warning('Products sync failed: unexpected payload', ['payload' => $data]);
                return redirect()->route('products.index')->with('error', 'Failed to sync products (invalid data).');
            }

            foreach ($data as $item) {
                // Be explicit about attributes we set to avoid mass-assignment surprises
                $attributes = [
                    'name' => $item['title'] ?? null,
                    'price' => isset($item['price']) ? $item['price'] : 0,
                    'stock' => rand(1, 100),
                    'description' => $item['description'] ?? null,
                ];

                if (empty($attributes['name'])) {
                    // Skip malformed records
                    continue;
                }

                Product::updateOrCreate([
                    'name' => $attributes['name'],
                ], $attributes);
            }

            return redirect()->route('products.index')->with('success', 'Products synced successfully!');
        } catch (\Exception $e) {
            Log::error('Products sync exception', ['exception' => $e]);
            return redirect()->route('products.index')->with('error', 'An error occurred while syncing products.');
        }
    }
}
