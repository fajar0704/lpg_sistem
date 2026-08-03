<?php

namespace App\Http\Controllers;

use App\Models\SubPangkalanTransaction;
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

        $query = SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->where('transaction_type', '!=', 'exchange');

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('transaction_date', $request->month);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('transaction_date', $request->date);
        }

        // Filter by Customer search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('customer', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('ktp', 'like', '%' . $search . '%');
            });
        }

        $recentDistributions = $query->with('customer')
            ->latest()
            ->paginate(10, ['*'], 'dist_page')
            ->withQueryString();

        // Get unique tabung types for filter
        $tabungTypes = SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->distinct()
            ->pluck('tabung_type');

        // Count filtered results (cloned query to get actual total count)
        $filteredCount = (clone $query)->count();

        $exchangeQuery = SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->where('transaction_type', 'exchange');

        if ($request->filled('exc_status')) {
            $exchangeQuery->where('status', $request->exc_status);
        }

        if ($request->filled('exc_month')) {
            $exchangeQuery->whereMonth('transaction_date', $request->exc_month);
        }

        $recentExchanges = $exchangeQuery->latest()
            ->paginate(5, ['*'], 'exc_page')
            ->withQueryString();

        if ($request->ajax() && $request->input('target') === 'exchange') {
            return response()->json([
                'html' => view('sub-pangkalan.partials.exchange-table-list', compact('recentExchanges'))->render()
            ]);
        }

        $refillQuery = \App\Models\PenjualanLangsung::where('customer_type', 'pengecer')
            ->where('no_ktp', $subPangkalan->ktp);

        if ($request->filled('refill_month')) {
            $refillQuery->whereMonth('transaction_date', $request->refill_month);
        }

        if ($request->filled('refill_date')) {
            $refillQuery->whereDate('transaction_date', $request->refill_date);
        }

        $recentRefills = $refillQuery->latest()
            ->paginate(5, ['*'], 'refill_page')
            ->withQueryString();

        if ($request->ajax() && $request->input('target') === 'refill') {
            return response()->json([
                'html' => view('sub-pangkalan.partials.refill-table-list', compact('recentRefills'))->render()
            ]);
        }

        return view('sub-pangkalan.dashboard', compact(
            'subPangkalan', 
            'recentDistributions', 
            'tabungTypes',
            'filteredCount',
            'recentExchanges',
            'recentRefills'
        ));
    }



    // Form penjualan ke pelanggan
    public function sellCreate()
    {
        $subPangkalan = auth()->user()->subPangkalan;
        $stocks = StockLpg::all();
        $customers = \App\Models\Customer::where('sub_pangkalan_id', $subPangkalan->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return view('sub-pangkalan.sell-create', compact('subPangkalan', 'stocks', 'customers'));
    }

    // Proses penjualan langsung (tanpa validasi admin)
    public function sellStore(Request $request)
    {
        $subPangkalan = auth()->user()->subPangkalan;

        $validated = $request->validate([
            'tabung_type'      => 'required|string|exists:stock_lpg,tabung_type',
            'quantity'         => 'required|integer|min:1',
            'customer_id'      => 'required|exists:customers,id',
            'transaction_date' => 'required|date',
            'notes'            => 'nullable|string|max:500',
        ]);

        $customer = \App\Models\Customer::where('sub_pangkalan_id', $subPangkalan->id)
            ->findOrFail($validated['customer_id']);

        if ($subPangkalan->stok_isi < $validated['quantity']) {
            return back()->withErrors(['quantity' => 'Stok isi tidak mencukupi. Stok isi saat ini: ' . $subPangkalan->stok_isi])->withInput();
        }

        // Langsung update stok: isi berkurang, kosong bertambah
        $subPangkalan->jual($validated['quantity']);

        SubPangkalanTransaction::create([
            'user_id'          => auth()->id(),
            'sub_pangkalan_id' => $subPangkalan->id,
            'customer_id'      => $customer->id,
            'tabung_type'      => $validated['tabung_type'],
            'quantity'         => $validated['quantity'],
            'type'             => 'out',
            'transaction_type' => 'sell',
            'customer_type'    => $customer->category === 'usaha_mikro' ? 'usaha' : 'rumah_tangga',
            'transaction_date' => $validated['transaction_date'],
            'status'           => 'approved',
            'notes'            => $validated['notes'] ?? null,
        ]);

        return redirect()->route('sub-pangkalan.dashboard')
            ->with('success', "Penjualan {$validated['quantity']} tabung berhasil. Stok isi: {$subPangkalan->fresh()->stok_isi}, Stok kosong: {$subPangkalan->fresh()->stok_kosong}");
    }

    // Form pengajuan tukar tabung kosong
    public function exchangeCreate(Request $request)
    {
        $subPangkalan = auth()->user()->subPangkalan;
        $stocks = StockLpg::all();

        $query = SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->where('transaction_type', 'exchange');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->whereMonth('transaction_date', $request->month);
        }

        $exchanges = $query->latest()
            ->paginate(5, ['*'], 'exchange_page')
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('sub-pangkalan.partials.exchange-history-list', compact('exchanges'))->render()
            ]);
        }

        return view('sub-pangkalan.exchange-create', compact('subPangkalan', 'stocks', 'exchanges'));
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

        // Stok kosong tidak dikurangi sekarang, menunggu konfirmasi Admin

        SubPangkalanTransaction::create([
            'user_id'          => auth()->id(),
            'sub_pangkalan_id' => $subPangkalan->id,
            'tabung_type'      => $validated['tabung_type'],
            'quantity'         => $validated['quantity'],
            'type'             => 'in',
            'transaction_type' => 'exchange',
            'transaction_date' => $validated['transaction_date'],
            'status'           => 'pending',
            'notes'            => $validated['notes'] ?? null,
        ]);

        return redirect()->route('sub-pangkalan.dashboard')
            ->with('success', 'Pengajuan penukaran tabung kosong berhasil dikirim, menunggu validasi admin.');
    }

    public function history(Request $request)
    {
        $user = auth()->user();
        $subPangkalan = $user->subPangkalan;

        if (!$subPangkalan) {
            return redirect()->route('login')->with('error', 'Sub Pangkalan tidak ditemukan.');
        }

        $query = SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->where('transaction_type', '!=', 'exchange');

        // Filter by transaction type
        if ($request->filled('transaction_type')) {
            $query->where('transaction_type', $request->transaction_type);
        }

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('transaction_date', $request->month);
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

        // Filter by Customer search or Notes
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('customer', function($subQ) use ($search) {
                    $subQ->where('name', 'like', '%' . $search . '%')
                         ->orWhere('ktp', 'like', '%' . $search . '%');
                })
                ->orWhere('notes', 'like', '%' . $search . '%');
            });
        }

        $distributions = $query->with(['validatedBy', 'customer'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('sub-pangkalan.partials.history-table-list', compact('distributions'))->render(),
                'total' => $distributions->total()
            ]);
        }

        // Get unique tabung types for filter
        $tabungTypes = SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->distinct()
            ->pluck('tabung_type');

        // Count filtered results
        $filteredCount = (clone $query)->count();

        return view('sub-pangkalan.history', compact('distributions', 'tabungTypes', 'filteredCount'));
    }

    public function clearHistory()
    {
        $user = auth()->user();
        $subPangkalan = $user->subPangkalan;

        if (!$subPangkalan) {
            return redirect()->route('login')->with('error', 'Sub Pangkalan tidak ditemukan.');
        }

        SubPangkalanTransaction::where('sub_pangkalan_id', $subPangkalan->id)
            ->where('transaction_type', '!=', 'exchange')
            ->delete();

        return redirect()->route('sub-pangkalan.history')
            ->with('success', 'Seluruh riwayat transaksi berhasil dikosongkan.');
    }

    public function confirmReceive(Request $request, SubPangkalanTransaction $distribution)
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

    // Daftar Pelanggan
    public function customerIndex(Request $request)
    {
        $query = \App\Models\Customer::where('sub_pangkalan_id', auth()->user()->sub_pangkalan_id);

        // Cari berdasarkan nama atau NIK (KTP)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('ktp', 'like', '%' . $search . '%');
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('sub-pangkalan.customers.index', compact('customers'));
    }

    // Formulir Tambah Pelanggan
    public function customerCreate()
    {
        return view('sub-pangkalan.customers.create');
    }

    // Simpan Pelanggan Baru dengan Tangkapan Kamera
    public function customerStore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'ktp'      => 'required|digits:16|unique:customers,ktp',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'category' => 'nullable|string|in:rumah_tangga,usaha_mikro,konsumen_umum',
            'photo'    => 'nullable|string', // Base64 dari webcam
            'kk_photo' => 'nullable|string', // Base64 dari webcam
        ]);

        $category = $validated['category'] ?? 'rumah_tangga';

        $photoPath = null;
        if ($request->filled('photo')) {
            $image_parts = explode(";base64,", $request->photo);
            if (count($image_parts) == 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'customer_' . time() . '.' . $image_type;
                $filePath = 'customer_photos/' . $fileName;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                $photoPath = $filePath;
            }
        }

        $kkPhotoPath = null;
        if ($category !== 'konsumen_umum' && $request->filled('kk_photo')) {
            $image_parts = explode(";base64,", $request->kk_photo);
            if (count($image_parts) == 2) {
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'customer_kk_' . time() . '.' . $image_type;
                $filePath = 'customer_photos/' . $fileName;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                $kkPhotoPath = $filePath;
            }
        }

        \App\Models\Customer::create([
            'name'             => $validated['name'],
            'ktp'              => $validated['ktp'],
            'phone'            => $validated['phone'] ?? null,
            'address'          => $validated['address'] ?? null,
            'category'         => $category,
            'is_active'        => true,
            'photo'            => $photoPath,
            'kk_photo'         => $kkPhotoPath,
            'sub_pangkalan_id' => auth()->user()->sub_pangkalan_id,
        ]);

        return redirect()->route('sub-pangkalan.customers.index')
            ->with('success', 'Pelanggan baru berhasil ditambahkan.');
    }

    // Detail Pelanggan
    public function customerShow(\App\Models\Customer $customer)
    {
        abort_if($customer->sub_pangkalan_id !== auth()->user()->sub_pangkalan_id, 403);

        // Ambil riwayat pembelian tabung gas di sub pangkalan ini
        $sales = \App\Models\SubPangkalanTransaction::where('customer_id', $customer->id)
            ->where('sub_pangkalan_id', auth()->user()->sub_pangkalan_id)
            ->latest()
            ->paginate(10);

        return view('sub-pangkalan.customers.show', compact('customer', 'sales'));
    }

    // Formulir Edit Pelanggan
    public function customerEdit(\App\Models\Customer $customer)
    {
        abort_if($customer->sub_pangkalan_id !== auth()->user()->sub_pangkalan_id, 403);

        return view('sub-pangkalan.customers.edit', compact('customer'));
    }

    // Perbarui Pelanggan
    public function customerUpdate(Request $request, \App\Models\Customer $customer)
    {
        abort_if($customer->sub_pangkalan_id !== auth()->user()->sub_pangkalan_id, 403);

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'ktp'      => 'required|digits:16|unique:customers,ktp,' . $customer->id,
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'category' => 'nullable|string|in:rumah_tangga,usaha_mikro,konsumen_umum',
            'photo'    => 'nullable|string', // Base64 dari webcam
            'kk_photo' => 'nullable|string', // Base64 dari webcam
        ]);

        $category = $validated['category'] ?? $customer->category ?? 'rumah_tangga';

        $photoPath = $customer->photo;
        if ($request->filled('photo')) {
            $image_parts = explode(";base64,", $request->photo);
            if (count($image_parts) == 2) {
                // Hapus foto KTP lama jika ada
                if ($customer->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->photo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->photo);
                }

                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'customer_' . time() . '.' . $image_type;
                $filePath = 'customer_photos/' . $fileName;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                $photoPath = $filePath;
            }
        }

        $kkPhotoPath = $customer->kk_photo;
        if ($category === 'konsumen_umum') {
            if ($customer->kk_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->kk_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->kk_photo);
            }
            $kkPhotoPath = null;
        } elseif ($request->filled('kk_photo')) {
            $image_parts = explode(";base64,", $request->kk_photo);
            if (count($image_parts) == 2) {
                if ($customer->kk_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->kk_photo)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->kk_photo);
                }

                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $fileName = 'customer_kk_' . time() . '.' . $image_type;
                $filePath = 'customer_photos/' . $fileName;
                \Illuminate\Support\Facades\Storage::disk('public')->put($filePath, $image_base64);
                $kkPhotoPath = $filePath;
            }
        }

        $customer->update([
            'name'     => $validated['name'],
            'ktp'      => $validated['ktp'],
            'phone'    => $validated['phone'] ?? null,
            'address'  => $validated['address'] ?? null,
            'category' => $category,
            'photo'    => $photoPath,
            'kk_photo' => $kkPhotoPath,
        ]);

        return redirect()->route('sub-pangkalan.customers.index')
            ->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    // Hapus Pelanggan
    public function customerDestroy(\App\Models\Customer $customer)
    {
        abort_if($customer->sub_pangkalan_id !== auth()->user()->sub_pangkalan_id, 403);

        // Hapus berkas foto dari storage
        if ($customer->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->photo);
        }

        if ($customer->kk_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->kk_photo)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->kk_photo);
        }

        $customer->delete();

        return redirect()->route('sub-pangkalan.customers.index')
            ->with('success', 'Pelanggan berhasil dihapus.');
    }

    public function profile()
    {
        $user = auth()->user();
        $subPangkalan = $user->subPangkalan;
        return view('sub-pangkalan.profile', compact('user', 'subPangkalan'));
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();
        $subPangkalan = $user->subPangkalan;

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
            'nama_ktp' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if (!$request->hasFile('photo') && $request->nama_ktp === $subPangkalan->nama_ktp && $request->phone === $subPangkalan->phone && $request->address === $subPangkalan->address && $request->email === $user->email) {
            return back()->with('warning', 'Tidak ada perubahan pada data profil Anda.');
        }

        if ($request->hasFile('photo')) {
            if ($user->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            $uploadedPhoto = $request->file('photo')->store('sub_pangkalan_photos', 'public');
            $user->photo = $uploadedPhoto;
            if (!$subPangkalan->photo) {
                $subPangkalan->photo = $uploadedPhoto;
            }
        }

        $subPangkalan->nama_ktp = $request->nama_ktp;
        $subPangkalan->phone = $request->phone;
        $subPangkalan->address = $request->address;
        $subPangkalan->save();

        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function profileDeletePhoto()
    {
        $user = \App\Models\User::find(auth()->id());
        if ($user && $user->photo) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($user->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            $user->photo = null;
            $user->save();
            return back()->with('success', 'Foto profil berhasil dihapus.');
        }
        return back()->with('warning', 'Tidak ada foto profil yang dapat dihapus.');
    }
}
