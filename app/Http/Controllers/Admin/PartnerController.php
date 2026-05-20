<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $partnerSearch = $request->input('partner_search');

        $partners = Partner::when($partnerSearch, function ($query) use ($partnerSearch) {
                $query->where('name', 'LIKE', '%' . $partnerSearch . '%');
            })
            ->latest()
            ->get();

        return view('admin.partners.index', compact('partners', 'partnerSearch'));
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name'     => 'required|string|max:255',
            'logo_url' => 'nullable|url|max:255',
        ]);

        // Simpan ke database
        Partner::create([
            'name'    => $request->name,
            'logo_url'    => $request->logo_url,
        ]);

        // Redirect ke daftar partner dengan pesan sukses
        return redirect()->route('admin.partners.index')
                         ->with('success', 'Partner berhasil ditambahkan!');
    }

    public function edit(Partner $partner)
    {
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'logo_url' => 'nullable|url|max:255',
        ]);

        $partner->update($data);

        return redirect()->route('admin.partners.index')
                         ->with('success', 'Partner berhasil diperbarui!');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();

        return redirect()->route('admin.partners.index')
                         ->with('success', 'Partner berhasil dihapus!');
    }
}
