<?php

namespace App\Http\Controllers;

use App\Models\SubPangkalan;
use App\Models\Distribution;
use App\Models\StockLpg;
use App\Models\StockBatch;
use App\Models\PenjualanLangsung;
use App\Models\User;
use App\Services\FifoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSubPangkalan = SubPangkalan::count();
        $totalCustomers = \App\Models\Customer::count();
        $stocks = StockLpg::all();
        $totalStokIsi = $stocks->sum('stok_isi');
        $totalStokKosong = $stocks->sum('stok_kosong');
        $pendingCount = Distribution::where('status', 'pending')->count();
        $totalJualLangsung = PenjualanLangsung::whereDate('transaction_date', today())->sum('quantity');
        $recentDistributions = Distribution::with(['subPangkalan', 'user'])
            ->latest()->take(5)->get();
        $recentPenjualan = PenjualanLangsung::with('user')
            ->latest()->take(5)->get();
        $stockAlerts = $stocks->filter(fn($s) => $s->isBelowSafety());

        return view('admin.dashboard', compact(
            'totalSubPangkalan',
            'totalCustomers',
            'totalStokIsi',
            'totalStokKosong',
            'pendingCount',
            'totalJualLangsung',
            'recentDistributions',
            'recentPenjualan',
            'stockAlerts',
            'stocks'
        ));
    }

    public function subPangkalanIndex()
    {
        $subPangkalan = SubPangkalan::withCount('distributions')
            ->latest()
            ->paginate(10);

        return view('admin.sub-pangkalan.index', compact('subPangkalan'));
    }

    public function subPangkalanCreate()
    {
        return view('admin.sub-pangkalan.create');
    }

    public function subPangkalanStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:sub_pangkalan,code',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:sub_pangkalan',
            'ktp' => 'required|string|max:20',
            'nama_ktp' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'alamat_ktp' => 'required|string',
            'photo' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $photoPath = null;
            if ($request->filled('photo')) {
                $image_parts = explode(";base64,", $request->photo);
                if (count($image_parts) == 2) {
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = 'ktp_' . time() . '.' . $image_type;
                    $filePath = 'sub_pangkalan_photos/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                    $photoPath = $filePath;
                }
            }

            $subPangkalan = SubPangkalan::create([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'ktp' => $validated['ktp'],
                'nama_ktp' => $validated['nama_ktp'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat_ktp' => $validated['alamat_ktp'],
                'photo' => $photoPath,
            ]);

            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Hash::make($validated['password']),
                'role' => $validated['role'],
                'sub_pangkalan_id' => $subPangkalan->id,
            ]);

            DB::commit();
            return redirect()->route('admin.sub-pangkalan.index')
                ->with('success', 'Sub Pangkalan beserta akun berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menambahkan data: ' . $e->getMessage())->withInput();
        }
    }

    public function subPangkalanEdit(SubPangkalan $subPangkalan)
    {
        return view('admin.sub-pangkalan.edit', compact('subPangkalan'));
    }

    public function subPangkalanUpdate(Request $request, SubPangkalan $subPangkalan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:sub_pangkalan,code,' . $subPangkalan->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'ktp' => 'required|string|max:20',
            'nama_ktp' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'alamat_ktp' => 'required|string',
            'photo' => 'nullable|string',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . optional($subPangkalan->user)->id,
            'password' => 'nullable|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $photoPath = $subPangkalan->photo;
            if ($request->filled('photo')) {
                $image_parts = explode(";base64,", $request->photo);
                if (count($image_parts) == 2) {
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = 'ktp_' . time() . '.' . $image_type;
                    $filePath = 'sub_pangkalan_photos/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                    
                    if ($photoPath) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
                    }
                    $photoPath = $filePath;
                }
            }

            $subPangkalan->update([
                'name' => $validated['name'],
                'code' => $validated['code'],
                'address' => $validated['address'],
                'phone' => $validated['phone'],
                'ktp' => $validated['ktp'],
                'nama_ktp' => $validated['nama_ktp'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tanggal_lahir' => $validated['tanggal_lahir'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'alamat_ktp' => $validated['alamat_ktp'],
                'photo' => $photoPath,
            ]);

            if ($subPangkalan->user) {
                $userData = [];
                if (!empty($validated['email'])) {
                    $userData['email'] = $validated['email'];
                }
                if ($request->filled('password')) {
                    $userData['password'] = \Hash::make($validated['password']);
                }
                if (!empty($userData)) {
                    $subPangkalan->user->update($userData);
                }
            }

            DB::commit();
            return redirect()->route('admin.sub-pangkalan.index')
                ->with('success', 'Sub Pangkalan berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update data: ' . $e->getMessage())->withInput();
        }
    }

    public function subPangkalanToggleStatus(SubPangkalan $subPangkalan)
    {
        $subPangkalan->update(['is_active' => !$subPangkalan->is_active]);

        return back()->with('success', 'Status Sub Pangkalan berhasil diubah.');
    }

    public function subPangkalanDetail(SubPangkalan $subPangkalan)
    {
        $distributions = Distribution::where('sub_pangkalan_id', $subPangkalan->id)
            ->with(['user', 'validatedBy'])
            ->latest()
            ->paginate(15);

        return view('admin.sub-pangkalan.detail', compact('subPangkalan', 'distributions'));
    }

    public function stockIndex()
    {
        $stocks = StockLpg::all();
        $batches = StockBatch::orderBy('received_date')->orderBy('id')->get()->groupBy('tabung_type');
        $outflows = \App\Models\StockOutflow::with('batch')->orderBy('transaction_date')->get()->groupBy('tabung_type');

        return view('admin.stock.index', compact('stocks', 'batches', 'outflows'));
    }

    public function stockCreate()
    {
        $currentStocks = StockLpg::all();
        // Map untuk JS hint: { "3kg": 10, "5kg": 20, ... }
        $stokKosongMap = $currentStocks->pluck('stok_kosong', 'tabung_type');
        return view('admin.stock.create', compact('currentStocks', 'stokKosongMap'));
    }

    public function stockStore(Request $request)
    {
        $validated = $request->validate([
            'tabung_type' => 'required|string|max:50',
            'initial_stock' => 'required|integer|min:1',
            'received_date' => 'required|date',
            'safety_stock' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $stock = StockLpg::firstOrNew(['tabung_type' => $validated['tabung_type']]);
            $stock->tabung_type = $validated['tabung_type'];
            $stock->safety_stock = $validated['safety_stock'];
            $stock->save();

            // Tambah stok isi pangkalan + otomatis kurangi stok kosong (ditukar ke agen)
            $stock->terimaStokDariAgen($validated['initial_stock']);

            // Buat batch FIFO baru
            $fifo = new FifoService();
            $fifo->tambahBatch(
                $validated['tabung_type'],
                $validated['initial_stock'],
                $validated['received_date'],
                auth()->id()
            );

            DB::commit();
            return redirect()->route('admin.stock.index')
                ->with('success', 'Stok masuk berhasil dicatat. Batch baru ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan stok: ' . $e->getMessage());
        }
    }

    public function stockEdit(StockLpg $stockLpg)
    {
        return view('admin.stock.edit', compact('stockLpg'));
    }

    public function stockUpdate(Request $request, StockLpg $stockLpg)
    {
        $validated = $request->validate([
            'stok_isi' => 'required|integer|min:0',
            'stok_kosong' => 'required|integer|min:0',
            'safety_stock' => 'required|integer|min:0',
        ]);

        $stockLpg->update([
            'stok_isi' => $validated['stok_isi'],
            'stok_kosong' => $validated['stok_kosong'],
            'current_stock' => $validated['stok_isi'],
            'safety_stock' => $validated['safety_stock'],
        ]);

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stok ' . $stockLpg->tabung_type . ' berhasil diperbarui.');
    }

    public function distributionIndex()
    {
        $distributions = Distribution::with(['subPangkalan', 'user', 'validatedBy'])
            ->where('transaction_type', '!=', 'sell')
            ->latest()
            ->paginate(15);

        return view('admin.distribution.index', compact('distributions'));
    }

    public function distributionCreate()
    {
        $subPangkalans = \App\Models\SubPangkalan::where('is_active', true)->get();
        $stocks = StockLpg::where('stok_isi', '>', 0)->get();
        return view('admin.distribution.create', compact('subPangkalans', 'stocks'));
    }

    public function distributionStore(Request $request)
    {
        $validated = $request->validate([
            'sub_pangkalan_id' => 'required|exists:sub_pangkalan,id',
            'tabung_type' => 'required|string|exists:stock_lpg,tabung_type',
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $stock = StockLpg::where('tabung_type', $validated['tabung_type'])->first();

        if (!$stock || $stock->stok_isi < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok isi pangkalan tidak mencukupi. Stok isi saat ini: ' . ($stock->stok_isi ?? 0)])->withInput();
        }

        DB::beginTransaction();
        try {
            // FIFO: keluarkan dari batch
            $fifo = new FifoService();
            $fifo->keluarkan(
                $validated['tabung_type'],
                $validated['quantity'],
                $validated['transaction_date'],
                'distribusi_sub'
            );
            
            // Langsung kurangi stok isi pangkalan
            $stock->kirimKeSubPangkalan($validated['quantity']);

            $distribution = Distribution::create([
                'user_id' => auth()->id(),
                'sub_pangkalan_id' => $validated['sub_pangkalan_id'],
                'tabung_type' => $validated['tabung_type'],
                'quantity' => $validated['quantity'],
                'type' => 'out',
                'transaction_type' => 'receive',
                'transaction_date' => $validated['transaction_date'],
                'status' => 'pending',
                'notes' => $validated['notes'],
            ]);

            // Update log fifo if needed
            \App\Models\StockOutflow::where('source', 'distribusi_sub')
                ->whereNull('sourceable_id')
                ->update([
                    'sourceable_id' => $distribution->id,
                    'sourceable_type' => \App\Models\Distribution::class
                ]);

            DB::commit();
            return redirect()->route('admin.distribution.index')
                ->with('success', 'Distribusi berhasil dicatat. Menunggu konfirmasi penerimaan dari Sub Pangkalan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan distribusi: ' . $e->getMessage());
        }
    }

    public function distributionApprove(Request $request, Distribution $distribution)
    {
        DB::beginTransaction();
        try {
            $stock = StockLpg::where('tabung_type', $distribution->tabung_type)->first();
            $subPangkalan = $distribution->subPangkalan;

            if ($distribution->transaction_type === 'exchange' || $distribution->transaction_type === 'return_kosong') {
                $stock?->terimaKosong($distribution->quantity);
                // The Sub Pangkalan's stok_kosong was already deducted when they requested the exchange
            } else {
                return back()->with('error', 'Tipe transaksi ini tidak valid untuk dikonfirmasi oleh Admin.');
            }

            $distribution->validated_by = auth()->id();
            $distribution->approve();

            DB::commit();
            return redirect()->route('admin.distribution.index')
                ->with('success', 'Pengembalian tabung kosong dikonfirmasi, stok kosong pangkalan bertambah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyetujui: ' . $e->getMessage());
        }
    }

    public function distributionReject(Request $request, Distribution $distribution)
    {
        if ($distribution->transaction_type === 'exchange' || $distribution->transaction_type === 'return_kosong') {
            $distribution->subPangkalan->increment('stok_kosong', $distribution->quantity);
        }
        
        $distribution->reject();
        $distribution->validated_by = auth()->id();
        $distribution->save();

        return redirect()->route('admin.distribution.index')
            ->with('success', 'Distribusi berhasil ditolak dan stok dikembalikan.');
    }

    public function distributionEdit(Distribution $distribution)
    {
        if ($distribution->status !== 'pending') {
            return back()->with('error', 'Hanya distribusi dengan status menunggu yang dapat diedit.');
        }

        $subPangkalans = \App\Models\SubPangkalan::where('is_active', true)->get();
        $stocks = StockLpg::where('stok_isi', '>', 0)->get();
        return view('admin.distribution.edit', compact('distribution', 'subPangkalans', 'stocks'));
    }

    public function distributionUpdate(Request $request, Distribution $distribution)
    {
        if ($distribution->status !== 'pending') {
            return back()->with('error', 'Distribusi sudah tidak dapat diubah.');
        }

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $stock = StockLpg::where('tabung_type', $distribution->tabung_type)->first();
            $diff = $validated['quantity'] - $distribution->quantity;

            if ($distribution->transaction_type === 'receive') {
                if ($diff > 0 && (!$stock || $stock->stok_isi < $diff)) {
                    return back()->withErrors(['quantity' => 'Stok isi pangkalan tidak mencukupi untuk penambahan ini.']);
                }
                
                if ($diff > 0) {
                    $stock->kirimKeSubPangkalan($diff);
                } elseif ($diff < 0) {
                    $stock->increment('stok_isi', abs($diff));
                    $stock->decrement('stock_out', abs($diff));
                    $stock->increment('current_stock', abs($diff));
                }
            } elseif ($distribution->transaction_type === 'exchange' || $distribution->transaction_type === 'return_kosong') {
                $subPangkalan = $distribution->subPangkalan;
                
                if ($diff > 0 && $subPangkalan->stok_kosong < $diff) {
                    return back()->withErrors(['quantity' => 'Stok kosong pengecer tidak mencukupi.']);
                }
                
                if ($diff > 0) {
                    $subPangkalan->decrement('stok_kosong', $diff);
                } elseif ($diff < 0) {
                    $subPangkalan->increment('stok_kosong', abs($diff));
                }
            }

            $distribution->update([
                'quantity' => $validated['quantity'],
                'transaction_date' => $validated['transaction_date'],
                'notes' => $validated['notes'],
            ]);

            DB::commit();
            return redirect()->route('admin.distribution.index')->with('success', 'Distribusi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui distribusi: ' . $e->getMessage());
        }
    }

    public function distributionDestroy(Distribution $distribution)
    {
        if ($distribution->status !== 'pending') {
            return back()->with('error', 'Hanya distribusi yang berstatus menunggu yang dapat dibatalkan.');
        }

        DB::beginTransaction();
        try {
            if ($distribution->transaction_type === 'receive') {
                $stock = StockLpg::where('tabung_type', $distribution->tabung_type)->first();
                if ($stock) {
                    $stock->increment('stok_isi', $distribution->quantity);
                    $stock->decrement('stock_out', $distribution->quantity);
                    $stock->increment('current_stock', $distribution->quantity);
                }
                \App\Models\StockOutflow::where('sourceable_id', $distribution->id)
                    ->where('sourceable_type', Distribution::class)
                    ->delete();
            } elseif ($distribution->transaction_type === 'exchange' || $distribution->transaction_type === 'return_kosong') {
                $subPangkalan = $distribution->subPangkalan;
                if ($subPangkalan) {
                    $subPangkalan->increment('stok_kosong', $distribution->quantity);
                }
            }

            $distribution->delete();
            DB::commit();
            return back()->with('success', 'Distribusi berhasil dibatalkan dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membatalkan distribusi: ' . $e->getMessage());
        }
    }

    public function distributionShow(Distribution $distribution)
    {
        return view('admin.distribution.show', compact('distribution'));
    }

    // Penjualan langsung ke pembeli
    public function penjualanIndex()
    {
        $penjualan = PenjualanLangsung::with('user')->latest()->paginate(15);
        return view('admin.penjualan.index', compact('penjualan'));
    }

    public function penjualanCreate()
    {
        $stocks = StockLpg::where('stok_isi', '>', 0)->get();
        $customers = \App\Models\Customer::where('is_active', true)->get();
        return view('admin.penjualan.create', compact('stocks', 'customers'));
    }

    public function penjualanStore(Request $request)
    {
        $validated = $request->validate([
            'tabung_type' => 'required|string|exists:stock_lpg,tabung_type',
            'quantity' => 'required|integer|min:1',
            'customer_id' => 'required|exists:customers,id',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $stock = StockLpg::where('tabung_type', $validated['tabung_type'])->first();
        $customer = \App\Models\Customer::findOrFail($validated['customer_id']);

        if (!$stock || $stock->stok_isi < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok isi tidak mencukupi. Stok isi saat ini: ' . ($stock->stok_isi ?? 0)])->withInput();
        }

        // Validasi Kuota Bulanan
        $maxQuota = $customer->getMaxQuota($validated['tabung_type']);
        $usedQuota = $customer->getUsedQuotaThisMonth($validated['tabung_type']);
        
        if ($usedQuota + $validated['quantity'] > $maxQuota) {
            return back()->withErrors(['quantity' => "Kuota pembelian sudah habis. Sisa kuota bulan ini: " . max(0, $maxQuota - $usedQuota) . " tabung."])->withInput();
        }

        DB::beginTransaction();
        try {
            $stock->jualLangsung($validated['quantity']);

            $penjualan = PenjualanLangsung::create([
                'user_id' => auth()->id(),
                'customer_id' => $customer->id,
                'tabung_type' => $validated['tabung_type'],
                'quantity' => $validated['quantity'],
                'customer_type' => $customer->category,
                'nama_pembeli' => $customer->name,
                'no_ktp' => $customer->ktp,
                'transaction_date' => $validated['transaction_date'],
                'notes' => $validated['notes'],
            ]);

            // FIFO: keluarkan dari batch
            $fifo = new FifoService();
            $fifo->keluarkan(
                $validated['tabung_type'],
                $validated['quantity'],
                $validated['transaction_date'],
                'penjualan_langsung',
                PenjualanLangsung::class,
                $penjualan->id
            );

            DB::commit();
            return redirect()->route('admin.penjualan.index')
                ->with('success', "Penjualan {$validated['quantity']} tabung {$validated['tabung_type']} berhasil dicatat.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan penjualan: ' . $e->getMessage());
        }
    }

    public function checkCustomerQuota(Request $request)
    {
        $customer = \App\Models\Customer::find($request->customer_id);
        if (!$customer) {
            return response()->json(['error' => 'Pelanggan tidak ditemukan'], 404);
        }

        $tabungType = $request->tabung_type ?? '3kg';
        $max = $customer->getMaxQuota($tabungType);
        $used = $customer->getUsedQuotaThisMonth($tabungType);
        $remaining = max(0, $max - $used);

        if ($remaining > 2) {
            $status = 'Aman';
            $color = 'text-green-600';
        } elseif ($remaining > 0) {
            $status = 'Hampir habis';
            $color = 'text-yellow-600';
        } else {
            $status = 'Habis';
            $color = 'text-red-600';
        }

        return response()->json([
            'customer' => [
                'name' => $customer->name,
                'ktp' => $customer->ktp,
                'category_label' => $customer->category_label,
                'address' => $customer->address,
            ],
            'max_quota' => $max,
            'used_quota' => $used,
            'remaining_quota' => $remaining,
            'status' => $status,
            'color' => $color,
            'last_transaction' => $customer->getLastTransactionDate(),
        ]);
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        if ($request->section === 'password') {
            $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ]);
            if (!\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }
            $user->password = \Hash::make($request->password);
            $user->save();
            return back()->with('success', 'Password berhasil diperbarui.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function reports()
    {
        $type = request('type', 'daily');
        $startDate = request('start_date', now()->startOfMonth());
        $endDate = request('end_date', now()->endOfMonth());
        $subPangkalanId = request('sub_pangkalan_id');

        $query = Distribution::with(['subPangkalan', 'user'])
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($subPangkalanId) {
            $query->where('sub_pangkalan_id', $subPangkalanId);
        }

        if ($type === 'monthly') {
            $query->whereYear('transaction_date', now()->year);
        }

        $distributions = $query->latest()->paginate(20);
        $subPangkalan = SubPangkalan::all();

        return view('admin.reports', compact('distributions', 'subPangkalan', 'type', 'startDate', 'endDate', 'subPangkalanId'));
    }

    public function exportPdf()
    {
        $type = request('type', 'daily');
        $startDate = request('start_date', now()->startOfMonth());
        $endDate = request('end_date', now()->endOfMonth());
        $subPangkalanId = request('sub_pangkalan_id');

        $query = Distribution::with(['subPangkalan', 'user'])
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($subPangkalanId) {
            $query->where('sub_pangkalan_id', $subPangkalanId);
        }

        $distributions = $query->latest()->get();
        $subPangkalan = SubPangkalan::all();

        $pdf = \PDF::loadView('admin.reports-pdf', compact('distributions', 'startDate', 'endDate'));
        return $pdf->download('laporan-distribusi-' . now()->format('Y-m-d') . '.pdf');
    }

    public function exportExcel()
    {
        $type = request('type', 'daily');
        $startDate = request('start_date', now()->startOfMonth());
        $endDate = request('end_date', now()->endOfMonth());
        $subPangkalanId = request('sub_pangkalan_id');

        $query = Distribution::with(['subPangkalan', 'user'])
            ->whereBetween('transaction_date', [$startDate, $endDate]);

        if ($subPangkalanId) {
            $query->where('sub_pangkalan_id', $subPangkalanId);
        }

        $distributions = $query->latest()->get();

        return \Excel::download(new \App\Exports\DistributionExport($distributions), 'laporan-distribusi-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function monitoringIndex()
    {
        $subPangkalans = \App\Models\SubPangkalan::with(['user'])->get();
        $distributions = Distribution::with(['subPangkalan', 'user', 'validatedBy'])
            ->latest()
            ->take(20)
            ->get();
        
        return view('admin.monitoring.index', compact('subPangkalans', 'distributions'));
    }
}
