<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use Illuminate\Http\Request;

class MechanicController extends Controller
{
    public function index()
    {
        return view('admin.mechanics', ['mechanics' => Mechanic::withCount('repairOrders')->latest()->get()]);
    }

    public function store(Request $r)
    {
        Mechanic::create($r->validate([
            'name' => 'required|string|max:255',
            'specialist' => 'nullable|string|max:255',
        ]));
        return back()->with('success', 'Mekanik ditambahkan');
    }

    public function update(Request $r, Mechanic $mechanic)
    {
        $mechanic->update($r->validate([
            'name' => 'required|string|max:255',
            'specialist' => 'nullable|string|max:255',
        ]));
        return back()->with('success', 'Mekanik diupdate');
    }

    public function destroy(Mechanic $mechanic)
    {
        $mechanic->delete();
        return back()->with('success', 'Mekanik dihapus');
    }
}
