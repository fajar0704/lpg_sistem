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
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        $customers = $query->latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
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
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'category' => 'required|in:rumah_tangga,usaha_mikro,pengecer',
        ]);

        Customer::create($request->all());

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil ditambahkan.');
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
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'category' => 'required|in:rumah_tangga,usaha_mikro,pengecer',
        ]);

        $customer->update($request->all());

        return redirect()->route('admin.customers.index')
            ->with('success', 'Pelanggan berhasil diupdate.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}
