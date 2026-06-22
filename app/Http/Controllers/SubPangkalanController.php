<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\StockLpg;
use Illuminate\Http\Request;

class SubPangkalanController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        $subPangkalan = $user->subPangkalan;

        if (!$subPangkalan) {
            return redirect()->route('login')->with('error', 'Sub Pangkalan tidak ditemukan.');
        }

        $query = Distribution::where('sub_pangkalan_id', $subPangkalan->id);

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by tabung type
        if ($request->filled('tabung_type')) {
            $query->where('tabung_type', $request->tabung_type);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $recentDistributions = $query->latest()->take(10)->get();

        // Get unique tabung types for filter
        $tabungTypes = Distribution::where('sub_pangkalan_id', $subPangkalan->id)
            ->distinct()
            ->pluck('tabung_type');

        // Count filtered results
        $filteredCount = $recentDistributions->count();

        return view('sub-pangkalan.dashboard', compact(
            'subPangkalan', 
            'recentDistributions', 
            'tabungTypes',
            'filteredCount'
        ));
    }

    // Form terima LPG dari pangkalan
    public function inputCreate()
    {
        $stocks = StockLpg::all();
        return view('sub-pangkalan.input-create', compact('stocks'));
    }

    // Simpan pengajuan terima LPG (perlu validasi admin)
    public function inputStore(Request $request)
    {
        $validated = $request->validate([
            'tabung_type'      => 'required|string|exists:stock_lpg,tabung_type',
            'quantity'         => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:500',
        ]);

        Distribution::create([
            'user_id'          => auth()->id(),
            'sub_pangkalan_id' => auth()->user()->sub_pangkalan_id,
            'tabung_type'      => $validated['tabung_type'],
            'quantity'         => $validated['quantity'],
            'type'             => 'in',
            'transaction_type' => 'receive',
            'transaction_date' => $validated['transaction_date'],
            'status'           => 'pending',
            'notes'            => $validated['notes'],
        ]);

        return redirect()->route('sub-pangkalan.dashboard')
            ->with('success', 'Pengajuan penerimaan LPG berhasil dikirim, menunggu validasi admin.');
    }

    // Form penjualan ke pelanggan
    public function sellCreate()
    {
        $subPangkalan = auth()->user()->subPangkalan;
        $stocks = StockLpg::all();
        return view('sub-pangkalan.sell-create', compact('subPangkalan', 'stocks'));
    }

    // Proses penjualan langsung (tanpa validasi admin)
    public function sellStore(Request $request)
    {
        $subPangkalan = auth()->user()->subPangkalan;

        $validated = $request->validate([
            'tabung_type'      => 'required|string|exists:stock_lpg,tabung_type',
            'quantity'         => 'required|integer|min:1',
            'customer_type'    => 'required|in:rumah_tangga,usaha',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:500',
        ]);

        if ($subPangkalan->stok_isi < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok isi tidak mencukupi. Stok isi saat ini: ' . $subPangkalan->stok_isi])->withInput();
        }

        // Langsung update stok: isi berkurang, kosong bertambah
        $subPangkalan->jual($validated['quantity']);

        Distribution::create([
            'user_id'          => auth()->id(),
            'sub_pangkalan_id' => $subPangkalan->id,
            'tabung_type'      => $validated['tabung_type'],
            'quantity'         => $validated['quantity'],
            'type'             => 'out',
            'transaction_type' => 'sell',
            'customer_type'    => $validated['customer_type'],
            'transaction_date' => $validated['transaction_date'],
            'status'           => 'approved',
            'notes'            => $validated['notes'],
        ]);

        return redirect()->route('sub-pangkalan.dashboard')
            ->with('success', "Penjualan {$validated['quantity']} tabung berhasil. Stok isi: {$subPangkalan->fresh()->stok_isi}, Stok kosong: {$subPangkalan->fresh()->stok_kosong}");
    }

    // Form pengajuan tukar tabung kosong
    public function exchangeCreate()
    {
        $subPangkalan = auth()->user()->subPangkalan;
        $stocks = StockLpg::all();
        return view('sub-pangkalan.exchange-create', compact('subPangkalan', 'stocks'));
    }

    // Simpan pengajuan tukar tabung kosong (perlu validasi admin)
    public function exchangeStore(Request $request)
    {
        $subPangkalan = auth()->user()->subPangkalan;

        $validated = $request->validate([
            'tabung_type'      => 'required|string|exists:stock_lpg,tabung_type',
            'quantity'         => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:500',
        ]);

        if ($subPangkalan->stok_kosong < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Tabung kosong tidak mencukupi. Stok kosong saat ini: ' . $subPangkalan->stok_kosong])->withInput();
        }

        // Langsung kurangi stok kosong sub pangkalan
        $subPangkalan->decrement('stok_kosong', $validated['quantity']);

        Distribution::create([
            'user_id'          => auth()->id(),
            'sub_pangkalan_id' => $subPangkalan->id,
            'tabung_type'      => $validated['tabung_type'],
            'quantity'         => $validated['quantity'],
            'type'             => 'in',
            'transaction_type' => 'exchange',
            'transaction_date' => $validated['transaction_date'],
            'status'           => 'pending',
            'notes'            => $validated['notes'],
        ]);

        return redirect()->route('sub-pangkalan.dashboard')
            ->with('success', 'Pengajuan penukaran tabung kosong berhasil dikirim, menunggu validasi admin.');
    }

    public function history()
    {
        $user = auth()->user();
        $distributions = Distribution::where('sub_pangkalan_id', $user->sub_pangkalan_id)
            ->with(['validatedBy'])
            ->latest()
            ->paginate(15);

        return view('sub-pangkalan.history', compact('distributions'));
    }

    public function confirmReceive(Request $request, Distribution $distribution)
    {
        $user = auth()->user();
        if ($distribution->sub_pangkalan_id !== $user->sub_pangkalan_id) {
            abort(403);
        }

        if ($distribution->status !== 'pending' || $distribution->transaction_type !== 'receive') {
            return back()->with('error', 'Distribusi tidak dapat dikonfirmasi.');
        }

        \DB::beginTransaction();
        try {
            // Tambahkan stok isi sub pangkalan
            $subPangkalan = $distribution->subPangkalan;
            $subPangkalan->terimaLpg($distribution->quantity);

            $distribution->status = 'approved';
            $distribution->validated_by = $user->id;
            $distribution->validated_at = now();
            $distribution->save();

            \DB::commit();
            return back()->with('success', 'Penerimaan tabung berhasil dikonfirmasi. Stok isi Anda bertambah.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Gagal mengonfirmasi: ' . $e->getMessage());
        }
    }
}
