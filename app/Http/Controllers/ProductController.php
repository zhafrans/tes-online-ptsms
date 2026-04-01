<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $products = Product::paginate(10);
        return $this->sendResponse($products, 'Products list fetched successfully');
    }

    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());
        return $this->sendResponse($product, 'Product created successfully', 201);
    }

    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return $this->sendResponse($product, 'Product details fetched successfully');
    }

    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());
        return $this->sendResponse($product, 'Product updated successfully');
    }

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        
        return $this->sendResponse(null, 'Product deleted successfully', 200);
    }
}
