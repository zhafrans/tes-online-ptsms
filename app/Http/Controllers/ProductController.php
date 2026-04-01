<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(\App\Models\Product::paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $product = \App\Models\Product::create($validated);
        return response()->json($product, 201);
    }

    public function show(string $id)
    {
        return response()->json(\App\Models\Product::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $product = \App\Models\Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    public function destroy(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        
        return response()->json(null, 204);
    }
}
