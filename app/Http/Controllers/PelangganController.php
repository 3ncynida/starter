<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\Penjualan;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua pelanggan untuk proses pengecekan status membership
        $pelanggan = Pelanggan::all();

        // Perbarui status membership setiap pelanggan (aktif / tidak)
        foreach ($pelanggan as $p) {
            $p->checkMembershipStatus();
        }

        // Ambil nilai diskon member dari setting (default 0 jika tidak ada)
        $diskon = \App\Models\Setting::get('diskon_member', 0);

        // Mulai query builder untuk filter dan pencarian
        $query = Pelanggan::query();

        // Filter berdasarkan status member (active / inactive)
        if ($request->has('member_status') && $request->member_status !== '') {
            if ($request->member_status === 'active') {
                $query->where('is_member', true)
                    ->where('member_expired', '>', now());
            } elseif ($request->member_status === 'inactive') {
                $query->where(function ($q) {
                    $q->where('is_member', false)
                        ->orWhere('member_expired', '<=', now());
                });
            }
        }

        // Filter berdasarkan keyword pencarian (nama, telepon, alamat)
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('NamaPelanggan', 'like', "%{$search}%")
                    ->orWhere('NomorTelepon', 'like', "%{$search}%")
                    ->orWhere('Alamat', 'like', "%{$search}%");
            });
        }

        // Ambil data pelanggan yang sudah difilter, urutkan terbaru, dan paginasi
        $pelanggan = $query->orderBy('created_at', 'desc')->paginate(10);

        // Kirim data ke view
        return view('admin.pelanggan.index', compact('pelanggan', 'diskon'));
    }

    public function activateMember($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->activateMembership();

        // Tambahkan tanggal mulai member (hari ini)
        $pelanggan->member_start = now();
        $pelanggan->save();

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Membership berhasil diaktifkan untuk ' . $pelanggan->NamaPelanggan);
    }


    public function deactivateMember($id)
    {
        // Nonaktifkan membership pelanggan berdasarkan ID
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->deactivateMembership();

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Membership berhasil dinonaktifkan untuk ' . $pelanggan->NamaPelanggan);
    }

    // Toggle status member (aktif ⇆ nonaktif) secara langsung
    public function updateStatus($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Toggle status member
        $pelanggan->is_member = !$pelanggan->is_member;

        if ($pelanggan->is_member) {
            // Jika baru diaktifkan, set tanggal mulai dan expired
            $pelanggan->member_start = now();
            $pelanggan->member_expired = \Carbon\Carbon::now()->addMonth();
        } else {
            // Jika dinonaktifkan, hapus tanggal member_start dan expired
            $pelanggan->member_start = null;
            $pelanggan->member_expired = null;
        }

        $pelanggan->save();

        return back()->with('success', 'Status member berhasil diubah.');
    }


    public function create()
    {
        // Tampilkan form tambah pelanggan
        return view('admin.pelanggan.create');
    }

    public function store(Request $request)
    {
        // Validasi input pendaftaran pelanggan
        $request->validate([
            'NamaPelanggan' => 'required|string|max:255',
            'Alamat' => 'required|string',
            'NomorTelepon' => 'required|string|max:15',
        ]);

        // Simpan data pelanggan baru ke database
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
        // Ambil data pelanggan untuk diedit
        $pelanggan = Pelanggan::findOrFail($id);

        return view('admin.pelanggan.edit', compact('pelanggan'));
    }

public function update(Request $request, $id)
{
    // Validasi input update pelanggan
    $request->validate([
        'NamaPelanggan' => 'required|string|max:255',
        'Alamat' => 'required|string',
        'NomorTelepon' => 'required|string|max:15',
    ]);

    // Update data di database
    $pelanggan = Pelanggan::findOrFail($id);
    
    // Simpan nama pelanggan lama untuk pengecekan
    $namaPelangganLama = $pelanggan->NamaPelanggan;

    $pelanggan->update([
        'NamaPelanggan' => $request->NamaPelanggan,
        'Alamat' => $request->Alamat,
        'NomorTelepon' => $request->NomorTelepon,
    ]);

    // Update NamaPelanggan di tabel Penjualan jika nama pelanggan berubah
    if ($namaPelangganLama !== $request->NamaPelanggan) {
        Penjualan::where('PelangganID', $pelanggan->PelangganID)
            ->update(['NamaPelanggan' => $request->NamaPelanggan]);
    }

    return redirect()
        ->route('pelanggan.index')
        ->with('success', 'Pelanggan berhasil diperbarui');
}

    public function destroy($id)
    {
        // Hapus data pelanggan
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()
            ->route('pelanggan.index')
            ->with('success', 'Pelanggan berhasil dihapus');
    }
}