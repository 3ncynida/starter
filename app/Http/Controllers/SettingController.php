<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function edit()
    {
        $diskon = Setting::get('diskon_member', 0);
        return view('settings.edit', compact('diskon'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'diskon_member' => 'required|numeric|min:0|max:100',
        ]);

        Setting::updateOrCreate(
            ['key' => 'diskon_member'],
            ['value' => $request->diskon_member]
        );

        return redirect()->route('pelanggan.index')->with('success', 'Diskon berhasil diperbarui!');
    }
}
