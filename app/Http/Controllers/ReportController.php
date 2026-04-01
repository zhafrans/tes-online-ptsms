<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportPurchaseRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use ApiResponse;

    public function index(ReportPurchaseRequest $request)
    {
        $validated = $request->validated();

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];
        $productId = $validated['product_id'] ?? null;

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

        return $this->sendResponse($results, 'Report generated successfully');
    }
}
