<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index(Request $r)
    {
        $q = Vehicle::with('customer');
        if ($s = $r->search) {
            $q->whereAny(['plate_number', 'brand', 'model'], 'like', "%$s%");
        }
        return view('admin.vehicles', [
            'vehicles' => $q->latest()->paginate(20),
            'customers' => Customer::all(),
            'search' => $r->search,
        ]);
    }

    public function store(Request $r)
    {
        Vehicle::create($r->validate([
            'customer_id' => 'required|exists:customers,id',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'brand' => 'required|string|max:100|not_regex:/^\s+$/',
            'model' => 'required|string|max:100|not_regex:/^\s+$/',
        ]));
        return back()->with('success', 'Kendaraan ditambahkan');
    }

    public function update(Request $r, Vehicle $vehicle)
    {
        $vehicle->update($r->validate([
            'customer_id' => 'required|exists:customers,id',
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number,'.$vehicle->id,
            'brand' => 'required|string|max:100|not_regex:/^\s+$/',
            'model' => 'required|string|max:100|not_regex:/^\s+$/',
        ]));
        return back()->with('success', 'Kendaraan diupdate');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();
        return back()->with('success', 'Kendaraan dihapus');
    }
}
