<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $r)
    {
        $q = Customer::withCount('vehicles', 'repairOrders');
        if ($s = $r->search) {
            $q->whereAny(['name', 'email'], 'like', "%$s%");
        }
        return view('admin.customers', ['customers' => $q->latest()->paginate(20), 'search' => $r->search]);
    }

    public function store(Request $r)
    {
        Customer::create($r->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
        ]));
        return back()->with('success', 'Pelanggan ditambahkan');
    }

    public function update(Request $r, Customer $customer)
    {
        $customer->update($r->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
        ]));
        return back()->with('success', 'Pelanggan diupdate');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Pelanggan dihapus');
    }
}
