<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('items.product')->paginate(10);
        return response()->json($purchases);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $date = $validated['date'];
            $itemsData = $validated['items'];

            $totalPrice = 0;
            $itemsToInsert = [];

            foreach ($itemsData as $item) {
                $product = Product::findOrFail($item['product_id']);
                $itemPrice = $product->price;
                $totalPrice += ($item['qty'] * $itemPrice);

                $itemsToInsert[] = [
                    'product_id' => $product->id,
                    'qty' => $item['qty'],
                    'price' => $itemPrice,
                ];
            }

            $purchase = Purchase::create([
                'date' => $date,
                'total_price' => $totalPrice,
            ]);

            foreach ($itemsToInsert as $itemData) {
                $itemData['purchase_id'] = $purchase->id;
                \App\Models\PurchaseItem::create($itemData);
            }

            DB::commit();

            return response()->json($purchase->load('items.product'), 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to process purchase transaction', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $purchase = Purchase::with('items.product')->findOrFail($id);
        return response()->json($purchase);
    }
}
