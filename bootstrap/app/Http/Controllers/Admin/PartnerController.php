<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::query();

        // Logika Pencarian (Soal 3 UTS)
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $partners = $query->get();
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url' // Validasi URL sesuai nama kolom migration
        ]);

        Partner::create($request->all());
        return redirect()->route('admin.partners.index');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo_url' => 'required|url'
        ]);

        $partner->update($request->all());
        return redirect()->route('admin.partners.index');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('admin.partners.index');
    }
}
