<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'product_id' => 'nullable|integer|exists:products,id'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $productId = $request->product_id;

        $query = DB::table('purchases as p')
            ->join('purchase_items as pi', 'p.id', '=', 'pi.purchase_id')
            ->join('products as prod', 'pi.product_id', '=', 'prod.id')
            ->select(
                'p.date as tanggal',
                'prod.name as nama produk',
                DB::raw('COUNT(DISTINCT p.id) as total_transaksi'),
                DB::raw('SUM(pi.qty) as total_qty'),
                DB::raw('SUM(pi.qty * pi.price) as total_amount')
            )
            ->whereBetween('p.date', [$startDate, $endDate])
            ->groupBy('p.date', 'prod.id', 'prod.name');

        if ($productId) {
            $query->where('prod.id', $productId);
        }

        $results = $query->get();

        return response()->json($results);
    }
}
