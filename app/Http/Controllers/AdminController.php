<?php

namespace App\Http\Controllers;

use App\Models\SubPangkalan;
use App\Models\SubPangkalanTransaction;
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
        $countRumahTangga = \App\Models\Customer::whereNull('sub_pangkalan_id')->where('category', 'rumah_tangga')->count();
        $countUmkm = \App\Models\Customer::whereNull('sub_pangkalan_id')->where('category', 'usaha_mikro')->count();
        $countKonsumenUmum = \App\Models\Customer::whereNull('sub_pangkalan_id')->where('category', 'konsumen_umum')->count();
        $totalCustomers = $countRumahTangga + $countUmkm + $countKonsumenUmum + $totalSubPangkalan;
        $stocks = StockLpg::all();
        $totalStokIsi = $stocks->sum('stok_isi');
        $totalStokKosong = $stocks->sum('stok_kosong');
        $pendingCount = SubPangkalanTransaction::where('status', 'pending')->count();
        $totalJualLangsung = PenjualanLangsung::whereDate('transaction_date', today())->sum('quantity');
        $recentDistributions = SubPangkalanTransaction::with(['subPangkalan', 'user'])
            ->latest()->take(5)->get();
        $recentPenjualan = PenjualanLangsung::with('user')
            ->latest()->take(5)->get();
        $stockAlerts = $stocks->filter(fn($s) => $s->isBelowSafety());

        return view('admin.dashboard', compact(
            'totalSubPangkalan',
            'countRumahTangga',
            'countUmkm',
            'countKonsumenUmum',
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
        $subPangkalan = SubPangkalan::withCount('transactions')
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
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|unique:sub_pangkalan,code',
            'address'       => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'required|string|email|max:255|unique:users,email',
            'password'      => 'required|string|min:6',
            'role'          => 'required|string|in:sub_pangkalan',
            'ktp'           => 'required|digits:16|unique:sub_pangkalan,ktp',
            'nama_ktp'      => 'required|string|max:255',
            'tempat_lahir'  => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'alamat_ktp'    => 'required|string',
            'photo'         => 'required|string',
            'kk_photo'      => 'required|string',
        ], [
            'code.unique'   => 'Maaf, Kode Sub Pangkalan ini sudah terdaftar / digunakan oleh Sub Pangkalan lain.',
            'ktp.unique'    => 'Maaf, No. KTP / NIK ini sudah terdaftar pada Sub Pangkalan lain.',
            'ktp.digits'    => 'No. KTP / NIK harus terdiri dari 16 digit angka.',
            'email.unique'  => 'Maaf, Alamat Email ini sudah terdaftar pada akun pengguna lain.',
            'photo.required' => 'Dokumentasi Foto KTP / Pemilik wajib diambil sebelum menyimpan data.',
            'kk_photo.required' => 'Dokumentasi Foto KK wajib diambil sebelum menyimpan data.',
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

            $kkPhotoPath = null;
            if ($request->filled('kk_photo')) {
                $image_parts = explode(";base64,", $request->kk_photo);
                if (count($image_parts) == 2) {
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = 'kk_' . time() . '.' . $image_type;
                    $filePath = 'sub_pangkalan_photos/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                    $kkPhotoPath = $filePath;
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
                'kk_photo' => $kkPhotoPath,
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
            'name'          => 'required|string|max:255',
            'code'          => 'required|string|unique:sub_pangkalan,code,' . $subPangkalan->id,
            'address'       => 'nullable|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'ktp'           => 'required|digits:16|unique:sub_pangkalan,ktp,' . $subPangkalan->id,
            'nama_ktp'      => 'required|string|max:255',
            'tempat_lahir'  => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'alamat_ktp'    => 'required|string',
            'photo'         => 'nullable|string',
            'kk_photo'      => 'nullable|string',
            'email'         => 'nullable|string|email|max:255|unique:users,email,' . optional($subPangkalan->user)->id,
            'password'      => 'nullable|string|min:6',
        ], [
            'code.unique'  => 'Maaf, Kode Sub Pangkalan ini sudah terdaftar / digunakan oleh Sub Pangkalan lain.',
            'ktp.unique'   => 'Maaf, No. KTP / NIK ini sudah terdaftar pada Sub Pangkalan lain.',
            'ktp.digits'   => 'No. KTP / NIK harus terdiri dari 16 digit angka.',
            'email.unique' => 'Maaf, Alamat Email ini sudah terdaftar pada akun pengguna lain.',
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

            $kkPhotoPath = $subPangkalan->kk_photo;
            if ($request->filled('kk_photo')) {
                $image_parts = explode(";base64,", $request->kk_photo);
                if (count($image_parts) == 2) {
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = 'kk_' . time() . '.' . $image_type;
                    $filePath = 'sub_pangkalan_photos/' . $fileName;
                    \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                    
                    if ($kkPhotoPath) {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($kkPhotoPath);
                    }
                    $kkPhotoPath = $filePath;
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
                'kk_photo' => $kkPhotoPath,
            ]);

            if ($subPangkalan->user) {
                $isReset = \Hash::check('pangkalan123', $subPangkalan->user->password);
                $emailChanged = !empty($validated['email']) && $validated['email'] !== $subPangkalan->user->email;
                $passwordChanged = $request->filled('password');

                if (($emailChanged || $passwordChanged) && !$isReset) {
                    DB::rollBack();
                    return back()->with('error', 'Email atau password hanya dapat diubah setelah Anda melakukan Reset Password terlebih dahulu.')->withInput();
                }

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

    public function subPangkalanDestroy(SubPangkalan $subPangkalan)
    {
        DB::beginTransaction();
        try {
            // Hapus file foto KTP jika ada
            if ($subPangkalan->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($subPangkalan->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($subPangkalan->photo);
            }

            // Hapus file foto KK jika ada
            if ($subPangkalan->kk_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($subPangkalan->kk_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($subPangkalan->kk_photo);
            }
            
            // Hapus Sub Pangkalan. Karena database foreign key onDelete('cascade') diatur pada users dan sub_pangkalan_transactions,
            // baris user dan transaksi yang terasosiasi akan terhapus secara otomatis oleh database.
            $subPangkalan->delete();

            DB::commit();
            return redirect()->route('admin.sub-pangkalan.index')
                ->with('success', 'Sub Pangkalan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus Sub Pangkalan: ' . $e->getMessage());
        }
    }

    public function subPangkalanDetail(SubPangkalan $subPangkalan)
    {
        $distributions = SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->with(['user', 'validatedBy'])
            ->latest()
            ->paginate(15);

        return view('admin.sub-pangkalan.detail', compact('subPangkalan', 'distributions'));
    }

    public function subPangkalanResetPassword(SubPangkalan $subPangkalan)
    {
        $user = $subPangkalan->user;
        if (!$user) {
            return back()->with('error', 'Akun login untuk sub pangkalan ini tidak ditemukan.');
        }

        $user->password = \Hash::make('pangkalan123');
        $user->save();

        return back()->with('success', 'Password sub pangkalan berhasil di-reset menjadi "pangkalan123". Silakan minta sub pangkalan untuk segera menggantinya setelah login.');
    }

    public function stockIndex()
    {
        $stocks = StockLpg::all();
        $batches = StockBatch::orderBy('received_date')->orderBy('id')->get()->groupBy('tabung_type');
        $outflows = \App\Models\StockOutflow::with('batch')->orderBy('transaction_date', 'desc')->orderBy('id', 'desc')->get()->groupBy('tabung_type');

        return view('admin.stock.index', compact('stocks', 'batches', 'outflows'));
    }

    public function clearOutflowHistory($tabung_type)
    {
        \App\Models\StockOutflow::where('tabung_type', $tabung_type)->delete();

        return redirect()->route('admin.stock.index')
            ->with('success', 'Riwayat pengeluaran (FIFO tracking) untuk tabung ' . $tabung_type . ' berhasil dihapus.');
    }

    public function stockCreate()
    {
        $currentStocks = StockLpg::all();
        // Map untuk JS hint: { "3kg": 10, "5kg": 20, ... }
        $stokKosongMap = $currentStocks->pluck('stok_kosong', 'tabung_type');
        $stokIsiMap = $currentStocks->pluck('stok_isi', 'tabung_type');
        $maxStockMap = $currentStocks->mapWithKeys(function ($stock) {
            return [$stock->tabung_type => $stock->max_stock];
        });
        
        return view('admin.stock.create', compact('currentStocks', 'stokKosongMap', 'stokIsiMap', 'maxStockMap'));
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
            
            // Check capacity limit
            $newStokIsi = ($stock->stok_isi ?? 0) + $validated['initial_stock'];
            if ($newStokIsi > $stock->max_stock) {
                return back()->with('error', 'Gagal menyimpan stok: Jumlah stok baru untuk tabung ' . $validated['tabung_type'] . ' (' . $newStokIsi . ') melebihi kapasitas maksimum (' . $stock->max_stock . ').')->withInput();
            }

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
            'max_stock' => 'required|integer|min:1',
            'stok_isi' => 'required|integer|min:0',
            'stok_kosong' => 'required|integer|min:0',
            'safety_stock' => 'required|integer|min:0',
        ], [
            'max_stock.required' => 'Kapasitas Maksimum wajib diisi.',
            'max_stock.integer' => 'Kapasitas Maksimum harus berupa angka.',
            'max_stock.min' => 'Kapasitas Maksimum minimal 1.',
        ]);

        $oldMax = $stockLpg->max_stock;
        $newMax = $validated['max_stock'];
        $stokKosong = $validated['stok_kosong'];

        if ($newMax > $oldMax) {
            $diff = $newMax - $oldMax;
            if ($stokKosong === $stockLpg->stok_kosong) {
                $stokKosong += $diff;
            }
        }

        if ($validated['stok_isi'] > $newMax) {
            return back()->with('error', 'Gagal memperbarui stok: Jumlah stok isi (' . $validated['stok_isi'] . ') tidak boleh melebihi kapasitas maksimum (' . $newMax . ').')->withInput();
        }

        if ($stokKosong > $newMax) {
            return back()->with('error', 'Gagal memperbarui stok: Jumlah stok kosong (' . $stokKosong . ') tidak boleh melebihi kapasitas maksimum (' . $newMax . ').')->withInput();
        }

        $stockLpg->update([
            'max_stock' => $newMax,
            'stok_isi' => $validated['stok_isi'],
            'stok_kosong' => $stokKosong,
            'current_stock' => $validated['stok_isi'],
            'safety_stock' => $validated['safety_stock'],
        ]);

        return redirect()->route('admin.stock.index')
            ->with('success', 'Stok ' . $stockLpg->tabung_type . ' berhasil diperbarui.');
    }

    public function stockBatchEdit($batchId)
    {
        $batch = \App\Models\StockBatch::findOrFail($batchId);
        $stock = StockLpg::where('tabung_type', $batch->tabung_type)->first();
        return view('admin.stock.edit-batch', compact('batch', 'stock'));
    }

    public function stockBatchUpdate(Request $request, $batchId)
    {
        $batch = \App\Models\StockBatch::findOrFail($batchId);

        $validated = $request->validate([
            'quantity_in' => 'required|integer|min:1',
            'received_date' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            $diff = $validated['quantity_in'] - $batch->quantity_in;

            // Validate that we don't reduce quantity below what's already consumed
            if ($batch->quantity_remaining + $diff < 0) {
                return back()->with('error', 'Gagal memperbarui: Jumlah stok baru tidak boleh kurang dari jumlah yang sudah digunakan/terjual (' . ($batch->quantity_in - $batch->quantity_remaining) . ' tabung).');
            }

            // Adjust StockLpg totals
            $stock = StockLpg::where('tabung_type', $batch->tabung_type)->first();
            if ($stock) {
                if ($diff > 0) {
                    $newStokIsi = $stock->stok_isi + $diff;
                    if ($newStokIsi > $stock->max_stock) {
                        return back()->with('error', 'Gagal memperbarui batch: Jumlah stok isi baru (' . $newStokIsi . ') melebihi kapasitas maksimum (' . $stock->max_stock . ').')->withInput();
                    }
                    $kosongDikembalikan = min($diff, $stock->stok_kosong);
                    $stock->increment('stok_isi', $diff);
                    $stock->increment('stock_in', $diff);
                    $stock->increment('current_stock', $diff);
                    if ($kosongDikembalikan > 0) {
                        $stock->decrement('stok_kosong', $kosongDikembalikan);
                    }
                } elseif ($diff < 0) {
                    $absDiff = abs($diff);
                    $stock->decrement('stok_isi', $absDiff);
                    $stock->decrement('stock_in', $absDiff);
                    $stock->decrement('current_stock', $absDiff);
                    $stock->increment('stok_kosong', $absDiff);
                }
            }

            // Update batch
            $batch->update([
                'quantity_in' => $validated['quantity_in'],
                'quantity_remaining' => $batch->quantity_remaining + $diff,
                'received_date' => $validated['received_date'],
            ]);

            DB::commit();
            return redirect()->route('admin.stock.index')->with('success', 'Batch berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui batch: ' . $e->getMessage());
        }
    }

    public function stockBatchDestroy($batchId)
    {
        $batch = \App\Models\StockBatch::findOrFail($batchId);

        DB::beginTransaction();
        try {
            $stock = StockLpg::where('tabung_type', $batch->tabung_type)->first();
            if ($stock) {
                $stock->decrement('stok_isi', $batch->quantity_remaining);
                $stock->decrement('current_stock', $batch->quantity_remaining);
                $stock->decrement('stock_in', $batch->quantity_in);
                $stock->increment('stok_kosong', $batch->quantity_in);
            }

            \App\Models\StockOutflow::where('stock_batch_id', $batch->id)->delete();
            $batch->delete();

            DB::commit();
            return redirect()->route('admin.stock.index')->with('success', 'Batch berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus batch: ' . $e->getMessage());
        }
    }



    // Penjualan langsung ke pembeli
    public function penjualanIndex(Request $request)
    {
        $query = PenjualanLangsung::with('user');

        // Apply search filter (Nama or KTP)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_pembeli', 'like', '%' . $search . '%')
                  ->orWhere('no_ktp', 'like', '%' . $search . '%');
            });
        }

        // Apply customer category filter
        if ($request->filled('category')) {
            $query->where('customer_type', $request->input('category'));
        }

        // Apply tabung type filter
        if ($request->filled('tabung_type')) {
            $query->where('tabung_type', $request->input('tabung_type'));
        }

        // Apply month filter
        if ($request->filled('month')) {
            $query->whereMonth('transaction_date', $request->input('month'));
        }

        // Apply year filter
        if ($request->filled('year')) {
            $query->whereYear('transaction_date', $request->input('year'));
        }



        // Calculate statistics globally (not paginated)
        $todaySales = PenjualanLangsung::whereDate('transaction_date', today()->toDateString())->sum('quantity');
        $monthSales = PenjualanLangsung::whereMonth('transaction_date', today()->month)
            ->whereYear('transaction_date', today()->year)
            ->sum('quantity');
        $totalSalesCount = PenjualanLangsung::count();
        
        $sales3kg = PenjualanLangsung::where('tabung_type', '3kg')->sum('quantity');
        $sales5kg = PenjualanLangsung::where('tabung_type', '5kg')->sum('quantity');
        $sales12kg = PenjualanLangsung::where('tabung_type', '12kg')->sum('quantity');

        // Paginate results and maintain query params in pagination links
        $penjualan = $query->latest('transaction_date')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.penjualan.index', compact(
            'penjualan',
            'todaySales',
            'monthSales',
            'totalSalesCount',
            'sales3kg',
            'sales5kg',
            'sales12kg'
        ));
    }

    public function penjualanClear()
    {
        PenjualanLangsung::query()->delete();

        return redirect()->route('admin.penjualan.index')
            ->with('success', 'Seluruh riwayat penjualan langsung berhasil dikosongkan.');
    }

    public function penjualanDestroy($id)
    {
        $penjualan = PenjualanLangsung::findOrFail($id);
        $penjualan->delete();

        return redirect()->route('admin.penjualan.index')
            ->with('success', 'Data riwayat penjualan berhasil dihapus.');
    }

    public function penjualanCreate()
    {
        $stocks = StockLpg::all();
        return view('admin.penjualan.create', compact('stocks'));
    }

    public function penjualanStore(Request $request)
    {
        $validated = $request->validate([
            'tabung_type' => 'required|string|exists:stock_lpg,tabung_type',
            'quantity' => 'required|integer|min:1',
            'customer_id' => 'required|string',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $stock = StockLpg::where('tabung_type', $validated['tabung_type'])->first();

        if (!$stock || $stock->stok_isi < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok isi tidak mencukupi. Stok isi saat ini: ' . ($stock->stok_isi ?? 0)])->withInput();
        }

        $customerId = $validated['customer_id'];
        $isSub = str_starts_with($customerId, 'sub_');
        
        if ($isSub) {
            $id = str_replace('sub_', '', $customerId);
            $customer = \App\Models\SubPangkalan::findOrFail($id);
            $dbCustomerId = null;
            $customerType = 'pengecer';
            
            // Validasi Kuota Harian Sub Pangkalan (15 tabung per hari)
            $tabungType = $validated['tabung_type'];
            if ($tabungType === '3kg') {
                $maxQuota = 15;
                $usedQuota = \App\Models\PenjualanLangsung::where('customer_type', 'pengecer')
                    ->where('no_ktp', $customer->ktp)
                    ->where('tabung_type', $tabungType)
                    ->whereDate('transaction_date', $validated['transaction_date'])
                    ->sum('quantity');
                    
                if ($usedQuota + $validated['quantity'] > $maxQuota) {
                    return back()->withErrors(['quantity' => "Kuota pembelian Sub Pangkalan sudah habis. Sisa kuota hari ini: " . max(0, $maxQuota - $usedQuota) . " tabung."])->withInput();
                }
            }
        } else {
            $id = str_replace('cust_', '', $customerId);
            if (!str_starts_with($customerId, 'cust_') && !str_starts_with($customerId, 'sub_')) {
                $id = $customerId;
            }
            $customer = \App\Models\Customer::findOrFail($id);
            $dbCustomerId = $customer->id;
            $customerType = $customer->category;
            
            // Validasi Kuota Bulanan
            $maxQuota = $customer->getMaxQuota($validated['tabung_type']);
            $usedQuota = $customer->getUsedQuotaThisMonth($validated['tabung_type']);
            
            if ($maxQuota !== 999 && $usedQuota + $validated['quantity'] > $maxQuota) {
                return back()->withErrors(['quantity' => "Kuota pembelian sudah habis. Sisa kuota bulan ini: " . max(0, $maxQuota - $usedQuota) . " tabung."])->withInput();
            }
        }

        DB::beginTransaction();
        try {
            if ($isSub) {
                // Pangkalan ke Sub Pangkalan: isi pangkalan berkurang, kosong TIDAK bertambah
                $stock->kirimKeSubPangkalan($validated['quantity']);
                
                // Sub Pangkalan isi bertambah
                $customer->terimaLpg($validated['quantity']);
            } else {
                // Pangkalan ke Pelanggan Umum: isi pangkalan berkurang, kosong pangkalan otomatis bertambah
                $stock->jualLangsung($validated['quantity']);
            }

            $penjualan = PenjualanLangsung::create([
                'user_id' => auth()->id(),
                'customer_id' => $dbCustomerId,
                'tabung_type' => $validated['tabung_type'],
                'quantity' => $validated['quantity'],
                'customer_type' => $customerType,
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

    public function confirmReturnKosong(Request $request, SubPangkalanTransaction $distribution)
    {
        if ($distribution->status !== 'pending' || !in_array($distribution->transaction_type, ['return_kosong', 'exchange'])) {
            return back()->with('error', 'Data tidak valid atau sudah diproses.');
        }

        $subPangkalan = $distribution->subPangkalan;
        if (!$subPangkalan) {
            return back()->with('error', 'Sub Pangkalan tidak ditemukan.');
        }

        if ($subPangkalan->stok_kosong < $distribution->quantity) {
            return back()->with('error', 'Stok kosong Sub Pangkalan tidak mencukupi (saat ini: ' . $subPangkalan->stok_kosong . ').');
        }

        $stock = StockLpg::where('tabung_type', $distribution->tabung_type)->first();
        if (!$stock) {
            return back()->with('error', 'Stok master untuk tipe tabung ini tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // Sub Pangkalan stok kosong berkurang
            $subPangkalan->decrement('stok_kosong', $distribution->quantity);

            // Pangkalan stok kosong bertambah
            $stock->terimaKosong($distribution->quantity);

            // Update status transaksi
            $distribution->update([
                'status' => 'approved',
                'validated_by' => auth()->id(),
                'validated_at' => now(),
            ]);

            DB::commit();
            return back()->with('success', "Pengembalian {$distribution->quantity} tabung kosong dari {$subPangkalan->name} berhasil dikonfirmasi.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses konfirmasi: ' . $e->getMessage());
        }
    }

    public function checkCustomerQuota(Request $request)
    {
        $customerId = $request->customer_id;
        
        if (str_starts_with($customerId, 'sub_')) {
            $id = str_replace('sub_', '', $customerId);
            $customer = \App\Models\SubPangkalan::find($id);
            if (!$customer) {
                return response()->json(['error' => 'Sub Pangkalan tidak ditemukan'], 404);
            }
            
            $tabungType = $request->tabung_type ?? '3kg';
            $max = ($tabungType === '3kg') ? 15 : 999;
            $used = \App\Models\PenjualanLangsung::where('customer_type', 'pengecer')
                ->where('no_ktp', $customer->ktp)
                ->where('tabung_type', $tabungType)
                ->whereDate('transaction_date', today())
                ->sum('quantity');
                
            if ($max === 999) {
                $remaining = 999;
                $status = 'Aman';
                $color = 'text-green-600';
            } else {
                $remaining = max(0, $max - $used);
                if ($remaining > 5) {
                    $status = 'Aman';
                    $color = 'text-green-600';
                } elseif ($remaining > 0) {
                    $status = 'Hampir habis';
                    $color = 'text-yellow-600';
                } else {
                    $status = 'Habis';
                    $color = 'text-red-600';
                }
            }
            
            $lastTx = \App\Models\PenjualanLangsung::where('customer_type', 'pengecer')
                ->where('no_ktp', $customer->ktp)
                ->latest('transaction_date')
                ->latest('created_at')
                ->first();

            $lastTxStr = 'Belum ada transaksi';
            if ($lastTx) {
                $dateStr = \Carbon\Carbon::parse($lastTx->transaction_date)->translatedFormat('d M Y');
                $timeStr = \Carbon\Carbon::parse($lastTx->created_at)->timezone('Asia/Jakarta')->format('H:i');
                $lastTxStr = "{$dateStr} ({$timeStr} WIB)";
            }

            return response()->json([
                'customer' => [
                    'name' => $customer->name,
                    'ktp' => $customer->ktp,
                    'category_label' => 'Sub Pangkalan (Pengecer)',
                    'address' => $customer->address,
                ],
                'max_quota' => $max,
                'used_quota' => $used,
                'remaining_quota' => $remaining,
                'status' => $status,
                'color' => $color,
                'last_transaction' => $lastTxStr,
                'quota_label' => 'Hari Ini'
            ]);
        }

        $id = str_replace('cust_', '', $customerId);
        if (!str_starts_with($customerId, 'cust_') && !str_starts_with($customerId, 'sub_')) {
            $id = $customerId;
        }

        $customer = \App\Models\Customer::find($id);
        if (!$customer) {
            return response()->json(['error' => 'Pelanggan tidak ditemukan'], 404);
        }

        $tabungType = $request->tabung_type ?? '3kg';
        $max = $customer->getMaxQuota($tabungType);
        $used = $customer->getUsedQuotaThisMonth($tabungType);
        
        if ($max === 999) {
            $remaining = 999;
            $status = 'Aman';
            $color = 'text-green-600';
        } else {
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

    public function searchCustomers(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        
        $results = [];

        // Jika kategori kosong atau bukan pengecer, cari di tabel customers
        if (empty($category) || $category !== 'pengecer') {
            $customerQuery = \App\Models\Customer::where('is_active', true);
            
            if ($category) {
                $customerQuery->where('category', $category);
            }
            
            if ($search) {
                $customerQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('ktp', 'like', '%' . $search . '%');
                });
            }
            
            $customers = $customerQuery->take(15)->get();
            foreach ($customers as $c) {
                $results[] = [
                    'id' => 'cust_' . $c->id,
                    'name' => $c->name,
                    'ktp' => $c->ktp
                ];
            }
        }

        // Jika kategori kosong atau pengecer, cari di tabel sub_pangkalan
        if (empty($category) || $category === 'pengecer') {
            $subQuery = \App\Models\SubPangkalan::where('is_active', true);
            
            if ($search) {
                $subQuery->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('code', 'like', '%' . $search . '%')
                      ->orWhere('ktp', 'like', '%' . $search . '%');
                });
            }
            
            $subs = $subQuery->take(15)->get();
            foreach ($subs as $s) {
                $results[] = [
                    'id' => 'sub_' . $s->id,
                    'name' => $s->name,
                    'ktp' => $s->ktp ?: $s->code
                ];
            }
        }

        return response()->json($results);
    }

    public function profile()
    {
        $user = auth()->user();
        $totalPenjualanLangsung = PenjualanLangsung::count();
        $totalSubPangkalan = SubPangkalan::count();
        $totalStokIsi = StockLpg::sum('stok_isi');

        return view('admin.profile.index', compact('user', 'totalPenjualanLangsung', 'totalSubPangkalan', 'totalStokIsi'));
    }

    public function profileDeletePhoto()
    {
        $user = auth()->user();
        if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
        }
        $user->photo = null;
        $user->save();

        return back()->with('success', 'Foto profil berhasil dihapus.');
    }

    public function profileDeleteLoginLogo()
    {
        $oldLogo = \App\Models\Setting::getValue('login_logo');
        if ($oldLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldLogo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
        }
        \App\Models\Setting::setValue('login_logo', null);

        return back()->with('success', 'Logo login kustom berhasil dihapus dan dikembalikan ke logo default.');
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

        if ($request->section === 'login_settings') {
            $request->validate([
                'login_title' => 'required|string|max:100',
                'login_subtitle' => 'required|string|max:200',
                'login_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            $currentTitle = \App\Models\Setting::getValue('login_title');
            $currentSubtitle = \App\Models\Setting::getValue('login_subtitle');

            if (!$request->hasFile('login_logo') && $request->login_title === $currentTitle && $request->login_subtitle === $currentSubtitle) {
                return back()->with('warning', 'Tidak ada perubahan pada pengaturan halaman login.');
            }

            if ($request->hasFile('login_logo')) {
                $oldLogo = \App\Models\Setting::getValue('login_logo');
                if ($oldLogo && \Illuminate\Support\Facades\Storage::disk('public')->exists($oldLogo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($oldLogo);
                }
                $path = $request->file('login_logo')->store('login-assets', 'public');
                \App\Models\Setting::setValue('login_logo', $path);
            }

            \App\Models\Setting::setValue('login_title', $request->login_title);
            \App\Models\Setting::setValue('login_subtitle', $request->login_subtitle);

            return back()->with('success', 'Pengaturan halaman login berhasil diperbarui.');
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if (!$request->hasFile('photo') && $request->name === $user->name && $request->email === $user->email) {
            return back()->with('warning', 'Tidak ada perubahan pada data profil Anda.');
        }

        if ($request->hasFile('photo')) {
            if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            $user->photo = $request->file('photo')->store('profile-photos', 'public');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    private function getReportData(\Illuminate\Http\Request $request)
    {
        $reportType = $request->input('report_type', 'penjualan');
        $period = $request->input('period', 'monthly');
        
        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();
        $label = '';

        if ($period === 'daily') {
            $date = $request->input('date', now()->format('Y-m-d'));
            $startDate = \Carbon\Carbon::parse($date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($date)->endOfDay();
            $label = $startDate->translatedFormat('d F Y');
        } elseif ($period === 'monthly') {
            $month = $request->input('month', now()->format('Y-m'));
            $startDate = \Carbon\Carbon::parse($month . '-01')->startOfMonth();
            $endDate = \Carbon\Carbon::parse($month . '-01')->endOfMonth();
            $label = $startDate->translatedFormat('F Y');
        } elseif ($period === 'yearly') {
            $year = $request->input('year', now()->format('Y'));
            $startDate = \Carbon\Carbon::create($year, 1, 1)->startOfYear();
            $endDate = \Carbon\Carbon::create($year, 1, 1)->endOfYear();
            $label = "Tahun " . $year;
        }

        $data = [
            'reportType' => $reportType,
            'period' => $period,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'label' => $label,
            'records' => collect(),
            'stockSummary' => null,
            'subPangkalansList' => \App\Models\SubPangkalan::all(),
            'request' => $request,
        ];

        if ($reportType === 'penjualan') {
            $query = \App\Models\PenjualanLangsung::whereBetween('transaction_date', [$startDate, $endDate]);

            if ($request->filled('customer_type')) {
                $query->where('customer_type', $request->input('customer_type'));
            }

            if ($request->filled('sub_pangkalan_id')) {
                $subPangkalan = \App\Models\SubPangkalan::find($request->input('sub_pangkalan_id'));
                if ($subPangkalan) {
                    $query->where('customer_type', 'pengecer')
                          ->where('no_ktp', $subPangkalan->ktp);
                }
            }

            $data['records'] = $query->latest()->get();
        } elseif ($reportType === 'stok') {
            $masukRestok = \App\Models\StockBatch::whereBetween('received_date', [$startDate, $endDate])->sum('quantity_in');
            $masukKosong = 0;
            $masukTotal = $masukRestok + $masukKosong;

            $keluarTotal = \App\Models\PenjualanLangsung::whereBetween('transaction_date', [$startDate, $endDate])->sum('quantity');

            $stockLpg = \App\Models\StockLpg::first();
            $currentTotal = $stockLpg ? ($stockLpg->stok_isi + $stockLpg->stok_kosong) : 0;
            
            $outAfter = \App\Models\PenjualanLangsung::where('transaction_date', '>', $endDate)->sum('quantity');
            
            $inRestokAfter = \App\Models\StockBatch::where('received_date', '>', $endDate)->sum('quantity_in');
            $inKosongAfter = 0;
            
            $inAfter = $inRestokAfter + $inKosongAfter;
            
            $stokAkhirPeriode = $currentTotal + $outAfter - $inAfter;
            $stokAwal = $stokAkhirPeriode + $keluarTotal - $masukTotal;

            $data['stockSummary'] = [
                'stokAwal' => $stokAwal,
                'masukRestok' => $masukRestok,
                'masukKosong' => $masukKosong,
                'masukTotal' => $masukTotal,
                'keluarTotal' => $keluarTotal,
                'stokAkhir' => $stokAkhirPeriode,
            ];
            
            $data['restokDetail'] = \App\Models\StockBatch::whereBetween('received_date', [$startDate, $endDate])->latest()->get();
            $data['penjualanDetail'] = \App\Models\PenjualanLangsung::whereBetween('transaction_date', [$startDate, $endDate])->latest()->get();
            $data['returnDetail'] = collect();

        } elseif ($reportType === 'pelanggan') {
            $customerType = $request->input('customer_type');
            $rt = collect();
            $umkm = collect();
            $konsumenUmum = collect();
            $pengecer = collect();

            if (empty($customerType) || $customerType === 'rumah_tangga') {
                $rt = \App\Models\Customer::whereNull('sub_pangkalan_id')->where('category', 'rumah_tangga')->get()->map(function($c) {
                    return (object)[
                        'name' => $c->name,
                        'category' => 'Rumah Tangga',
                        'phone' => $c->phone ?? '-',
                        'created_at' => $c->created_at,
                        'photo' => $c->photo,
                        'kk_photo' => $c->kk_photo,
                        'ktp' => $c->ktp,
                        'sub_pangkalan_name' => '-',
                    ];
                });
            }
            if (empty($customerType) || $customerType === 'usaha_mikro') {
                $umkm = \App\Models\Customer::whereNull('sub_pangkalan_id')->where('category', 'usaha_mikro')->get()->map(function($c) {
                    return (object)[
                        'name' => $c->name,
                        'category' => 'Usaha Mikro',
                        'phone' => $c->phone ?? '-',
                        'created_at' => $c->created_at,
                        'photo' => $c->photo,
                        'kk_photo' => $c->kk_photo,
                        'ktp' => $c->ktp,
                        'sub_pangkalan_name' => '-',
                    ];
                });
            }
            if (empty($customerType) || $customerType === 'konsumen_umum') {
                $konsumenUmum = \App\Models\Customer::whereNull('sub_pangkalan_id')->where('category', 'konsumen_umum')->get()->map(function($c) {
                    return (object)[
                        'name' => $c->name,
                        'category' => 'Konsumen Umum',
                        'phone' => $c->phone ?? '-',
                        'created_at' => $c->created_at,
                        'photo' => $c->photo,
                        'kk_photo' => null,
                        'ktp' => $c->ktp,
                        'sub_pangkalan_name' => '-',
                    ];
                });
            }
            if (empty($customerType) || $customerType === 'pengecer') {
                $pengecer = \App\Models\SubPangkalan::with('user')->get()->map(function($c) {
                    return (object)[
                        'name' => $c->name,
                        'category' => 'Pengecer',
                        'phone' => $c->phone ?? '-',
                        'created_at' => $c->created_at,
                        'photo' => $c->photo,
                        'kk_photo' => $c->kk_photo,
                        'ktp' => $c->ktp ?? '-',
                        'sub_pangkalan_name' => '-',
                    ];
                });
            }
            
            $data['records'] = $rt->concat($umkm)->concat($konsumenUmum)->concat($pengecer);
        } elseif ($reportType === 'pelanggan_sub_pangkalan') {
            $customerType = $request->input('customer_type');
            $subPangkalanId = $request->input('sub_pangkalan_id');
            
            $query = \App\Models\Customer::whereNotNull('sub_pangkalan_id');
            
            if ($request->filled('sub_pangkalan_id')) {
                $query->where('sub_pangkalan_id', $subPangkalanId);
            }
            
            if ($request->filled('customer_type')) {
                $query->where('category', $customerType);
            }
            
            $data['records'] = $query->with('subPangkalan')->get()->map(function($c) {
                return (object)[
                    'name' => $c->name,
                    'category' => $c->category === 'rumah_tangga' ? 'Rumah Tangga' : ($c->category === 'usaha_mikro' ? 'Usaha Mikro' : ($c->category === 'konsumen_umum' ? 'Konsumen Umum' : $c->category)),
                    'phone' => $c->phone ?? '-',
                    'created_at' => $c->created_at,
                    'photo' => $c->photo,
                    'kk_photo' => $c->kk_photo,
                    'ktp' => $c->ktp,
                    'sub_pangkalan_name' => optional($c->subPangkalan)->name ?? '-',
                ];
            });
        }

        return $data;
    }

    public function reports(\Illuminate\Http\Request $request)
    {
        $data = $this->getReportData($request);
        // We will paginate records if it's penjualan or pelanggan
        // tapi user minta export semua, jadi getReportData returns all. 
        // We can create a manual paginator for view if needed.
        if (in_array($data['reportType'], ['penjualan', 'pelanggan', 'pelanggan_sub_pangkalan'])) {
            // Paginate the collection manually for the view
            $currentPage = \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $currentItems = $data['records']->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $data['paginatedRecords'] = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($data['records']), $perPage, $currentPage, [
                'path' => \Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPath(),
                'query' => $request->query()
            ]);
        }
        
        return view('admin.reports', $data);
    }

    public function exportPdf(\Illuminate\Http\Request $request)
    {
        $data = $this->getReportData($request);
        $pdf = \PDF::loadView('admin.reports-pdf', $data);
        return $pdf->download('laporan-' . $data['reportType'] . '-' . \Str::slug($data['label']) . '.pdf');
    }

    public function exportExcel(\Illuminate\Http\Request $request)
    {
        $data = $this->getReportData($request);
        return \Excel::download(new \App\Exports\ReportExport($data), 'laporan-' . $data['reportType'] . '-' . \Str::slug($data['label']) . '.xlsx');
    }

    public function monitoringIndex(\Illuminate\Http\Request $request)
    {
        $subPangkalans = SubPangkalan::with('user')->get();
        
        $query = SubPangkalanTransaction::with(['subPangkalan', 'user'])
            ->whereIn('transaction_type', ['exchange', 'return_kosong']);

        // Filter by Sub Pangkalan
        if ($request->filled('sub_pangkalan_id')) {
            $query->where('sub_pangkalan_id', $request->sub_pangkalan_id);
        }

        // Filter by Month
        if ($request->filled('month')) {
            $query->whereMonth('transaction_date', $request->month);
        }

        $distributions = $query->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.monitoring.partials.activity-log-table', compact('distributions'))->render()
            ]);
        }

        $pendingConfirmations = SubPangkalanTransaction::where('status', 'pending')
            ->whereIn('transaction_type', ['exchange', 'return_kosong'])
            ->count();
            
        return view('admin.monitoring.index', compact('subPangkalans', 'distributions', 'pendingConfirmations'));
    }

    public function monitoringDetail(SubPangkalan $subPangkalan)
    {
        // Total Penjualan ke pelanggan
        $totalTransaksi = \App\Models\SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->where('transaction_type', 'sell')
            ->count();
            
        $totalTabungTerjual = \App\Models\SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->where('transaction_type', 'sell')
            ->sum('quantity');
            
        $penjualanHariIni = \App\Models\SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->where('transaction_type', 'sell')
            ->whereDate('transaction_date', today())
            ->sum('quantity');

        // Riwayat Transaksi Penjualan (Pengecer -> Pelanggan)
        $riwayatPenjualan = \App\Models\SubPangkalanTransaction::with('customer')->where('sub_pangkalan_id', $subPangkalan->id)
            ->where('transaction_type', 'sell')
            ->latest()
            ->paginate(10, ['*'], 'penjualan_page');

        // Riwayat Pembelian dari Pangkalan (Pangkalan -> Pengecer)
        $riwayatPembelian = \App\Models\PenjualanLangsung::where('customer_type', 'pengecer')
            ->where('no_ktp', $subPangkalan->ktp)
            ->latest()
            ->paginate(10, ['*'], 'pembelian_page');

        return view('admin.monitoring.detail', compact(
            'subPangkalan',
            'totalTransaksi',
            'totalTabungTerjual',
            'penjualanHariIni',
            'riwayatPenjualan',
            'riwayatPembelian'
        ));
    }
}
