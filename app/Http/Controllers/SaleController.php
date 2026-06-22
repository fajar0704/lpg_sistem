<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockBatch;
use App\Models\StockLpg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function create(Request $request)
    {
        $category  = $request->category;
        $customers = $category
            ? Customer::where('category', $category)->where('is_active', true)->get()
            : collect();
        $stocks    = StockLpg::all();

        $view = auth()->user()->isAdmin()
            ? 'admin.sales.create'
            : 'sub-pangkalan.sales.create';

        return view($view, compact('customers', 'stocks', 'category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id'  => 'required|exists:customers,id',
            'sale_date'    => 'required|date',
            'items'        => 'required|array|min:1',
            'items.*.tabung_type' => 'required|string|exists:stock_lpg,tabung_type',
            'items.*.quantity'    => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $totalQty = collect($request->items)->sum('quantity');
            $soldBy   = auth()->user()->isAdmin() ? 'admin' : 'sub_pangkalan';

            // Deduct FIFO per tabung type
            foreach ($request->items as $item) {
                $ok = StockBatch::deductFifo($item['tabung_type'], $item['quantity']);
                if (!$ok) {
                    DB::rollBack();
                    return back()->withErrors(['items' => 'Stok ' . $item['tabung_type'] . ' tidak mencukupi.'])->withInput();
                }
                // Update summary stock
                $stock = StockLpg::where('tabung_type', $item['tabung_type'])->first();
                if ($stock) {
                    $stock->updateStock($item['quantity'], 'out');
                }
            }

            $sale = Sale::create([
                'invoice_number'  => Sale::generateInvoice(),
                'customer_id'     => $request->customer_id,
                'user_id'         => auth()->id(),
                'sub_pangkalan_id'=> auth()->user()->sub_pangkalan_id,
                'sold_by'         => $soldBy,
                'sale_date'       => $request->sale_date,
                'total_quantity'  => $totalQty,
            ]);

            foreach ($request->items as $item) {
                SaleItem::create([
                    'sale_id'     => $sale->id,
                    'tabung_type' => $item['tabung_type'],
                    'quantity'    => $item['quantity'],
                ]);
            }

            DB::commit();

            $route = auth()->user()->isAdmin()
                ? 'admin.sales.index'
                : 'sub-pangkalan.sales.index';

            return redirect()->route($route)
                ->with('success', 'Penjualan berhasil dicatat. Invoice: ' . $sale->invoice_number);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan penjualan.')->withInput();
        }
    }

    public function index()
    {
        $isAdmin = auth()->user()->isAdmin();

        $query = Sale::with(['customer', 'user', 'items']);
        if (!$isAdmin) {
            $query->where('user_id', auth()->id());
        }
        $sales = $query->latest()->paginate(15);

        $view = $isAdmin ? 'admin.sales.index' : 'sub-pangkalan.sales.index';
        return view($view, compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['customer', 'user', 'items']);
        $view = auth()->user()->isAdmin()
            ? 'admin.sales.show'
            : 'sub-pangkalan.sales.show';
        return view($view, compact('sale'));
    }
}
