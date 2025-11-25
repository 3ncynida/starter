<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Kolom yang boleh diisi otomatis saat create/update
    protected $fillable = ['key', 'value'];

    // Fungsi untuk mengambil data setting berdasarkan key
    public static function get($key, $default = null)
    {
        // Cari data pertama dengan key yang diberikan
        $setting = self::where('key', $key)->first();

        // Kalau ketemu, ambil value-nya
        // Kalau tidak ada, pakai nilai default (jika ada)
        return $setting ? $setting->value : $default;
    }
}
