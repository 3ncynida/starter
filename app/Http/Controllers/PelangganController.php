<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $pelanggan = Pelanggan::all();
        // Update status membership untuk semua pelanggan
        foreach ($pelanggan as $p) {
            $p->checkMembershipStatus();
        }
        $diskon = \App\Models\Setting::get('diskon_member', 0);

        $query = Pelanggan::query();

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

        // Filter by search keyword
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('NamaPelanggan', 'like', "%{$search}%")
                    ->orWhere('NomorTelepon', 'like', "%{$search}%")
                    ->orWhere('Alamat', 'like', "%{$search}%");
            });
        }

        // Add membership status indicator with active/expired check
        $pelanggan = $query->orderBy('created_at', 'desc')->paginate(10);

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

    // Method baru untuk toggle status member
    public function updateStatus($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Toggle status member
        $pelanggan->is_member = !$pelanggan->is_member;

        // Jika diaktifkan, set expired date 1 BULAN dari sekarang
        if ($pelanggan->is_member) {
            $pelanggan->member_expired = \Carbon\Carbon::now()->addMonth();
        } else {
            $pelanggan->member_expired = null;
        }

        $pelanggan->save();

        return back()->with('success', 'Status member berhasil diubah.');
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
