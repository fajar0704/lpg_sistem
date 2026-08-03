<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->category === 'pengecer' || $request->category === 'sub_pangkalan') {
            return redirect()->route('admin.sub-pangkalan.index');
        }

        $query = Customer::query();

        // Search by name or KTP
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('ktp', 'like', '%' . $search . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }


        $customers = $query->latest()->paginate(15)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.customers.partials.customer-table-list', compact('customers'))->render()
            ]);
        }

        // Calculate statistics for general customers
        $totalCustomers = Customer::count();
        $rumahTanggaCount = Customer::where('category', 'rumah_tangga')->count();
        $usahaMikroCount = Customer::where('category', 'usaha_mikro')->count();
        $konsumenUmumCount = Customer::where('category', 'konsumen_umum')->count();

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'rumahTanggaCount',
            'usahaMikroCount',
            'konsumenUmumCount'
        ));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'ktp'      => 'required|digits:16|unique:customers,ktp',
            'phone'    => 'nullable|string|max:20|unique:customers,phone',
            'address'  => 'nullable|string|max:255',
            'category' => 'required|in:rumah_tangga,usaha_mikro,pengecer,konsumen_umum',
            'photo'    => 'nullable',
            'kk_photo' => 'nullable',
        ], [
            'ktp.unique'   => 'Maaf, No. KTP / NIK ini sudah terdaftar pada pelanggan lain.',
            'phone.unique' => 'Maaf, No. Telepon ini sudah terdaftar pada pelanggan lain.'
        ]);

        $photoPath = null;
        if ($request->filled('photo') && is_string($request->photo)) {
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
        if ($request->filled('kk_photo') && is_string($request->kk_photo)) {
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

        Customer::create([
            'name'     => $request->name,
            'ktp'      => $request->ktp,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'category' => $request->category,
            'photo'    => $photoPath,
            'kk_photo' => $kkPhotoPath,
            'is_active'=> true,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        $sales = $customer->penjualanLangsung()
            ->latest('transaction_date')
            ->paginate(10);

        return view('admin.customers.show', compact('customer', 'sales'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'ktp'      => 'required|digits:16|unique:customers,ktp,' . $customer->id,
            'phone'    => 'nullable|string|max:20|unique:customers,phone,' . $customer->id,
            'address'  => 'nullable|string|max:255',
            'category' => 'required|in:rumah_tangga,usaha_mikro,pengecer,konsumen_umum',
            'photo'    => 'nullable',
            'kk_photo' => 'nullable',
        ], [
            'ktp.unique'   => 'Maaf, No. KTP / NIK ini sudah terdaftar pada pelanggan lain.',
            'phone.unique' => 'Maaf, No. Telepon ini sudah terdaftar pada pelanggan lain.'
        ]);

        $photoPath = $customer->photo;
        if ($request->hasFile('photo')) {
            if ($customer->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->photo);
            }
            $photoPath = $request->file('photo')->store('customer_photos', 'public');
        } elseif ($request->filled('photo') && is_string($request->photo)) {
            $image_parts = explode(";base64,", $request->photo);
            if (count($image_parts) == 2) {
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
        if ($request->hasFile('kk_photo')) {
            if ($customer->kk_photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($customer->kk_photo)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($customer->kk_photo);
            }
            $kkPhotoPath = $request->file('kk_photo')->store('customer_photos', 'public');
        } elseif ($request->filled('kk_photo') && is_string($request->kk_photo)) {
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
            'name'     => $request->name,
            'ktp'      => $request->ktp,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'category' => $request->category,
            'photo'    => $photoPath,
            'kk_photo' => $kkPhotoPath,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil diupdate.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}
