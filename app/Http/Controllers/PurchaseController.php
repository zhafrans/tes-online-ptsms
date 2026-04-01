<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $purchases = Purchase::with('items.product')->paginate(10);
        return $this->sendResponse($purchases, 'Purchases list fetched successfully');
    }

    public function store(StorePurchaseRequest $request)
    {
        $validated = $request->validated();

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
                PurchaseItem::create($itemData);
            }

            DB::commit();

            return $this->sendResponse($purchase->load('items.product'), 'Purchase created successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Failed to process purchase', [$e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $purchase = Purchase::with('items.product')->findOrFail($id);
        return $this->sendResponse($purchase, 'Purchase details fetched successfully');
    }
}
