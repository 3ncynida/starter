<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();
        // Update status membership untuk semua pelanggan
        foreach ($pelanggan as $p) {
            $p->checkMembershipStatus();
        }
        $diskon = \App\Models\Setting::get('diskon_member', 0);

        return view('admin.pelanggan.index', compact('pelanggan', 'diskon'));
    }

    public function activateMember($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->activateMembership();

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Membership berhasil diaktifkan untuk ' . $pelanggan->NamaPelanggan);
    }

    public function deactivateMember($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->deactivateMembership();

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Membership berhasil dinonaktifkan untuk ' . $pelanggan->NamaPelanggan);
    }

    public function create()
    {
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'NamaPelanggan' => 'required|string|max:255',
            'Alamat' => 'required|string',
            'NomorTelepon' => 'required|string|max:15',
        ]);

        Pelanggan::create([
            'NamaPelanggan' => $request->NamaPelanggan,
            'Alamat' => $request->Alamat,
            'NomorTelepon' => $request->NomorTelepon,
        ]);

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'NamaPelanggan' => 'required|string|max:255',
            'Alamat' => 'required|string',
            'NomorTelepon' => 'required|string|max:15',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update([
            'NamaPelanggan' => $request->NamaPelanggan,
            'Alamat' => $request->Alamat,
            'NomorTelepon' => $request->NomorTelepon,
        ]);

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }
}
