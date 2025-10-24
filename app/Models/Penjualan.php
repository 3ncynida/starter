<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'PenjualanID';
    protected $fillable = [
        'PelangganID',
        'NamaPelanggan',
        'TanggalPenjualan',
        'TotalHarga',
        'Diskon',
        'UangTunai',
        'Kembalian',
    ];

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'PenjualanID');
    }

public function pelanggan()
{
    return $this->belongsTo(\App\Models\Pelanggan::class, 'PelangganID', 'PelangganID');
}

    public function getHargaAktifAttribute()
    {
        $sekarang = now()->toDateString();
        if (
            $this->Promosi &&
            $this->TanggalMulaiPromosi &&
            $this->TanggalSelesaiPromosi &&
            $sekarang >= $this->TanggalMulaiPromosi &&
            $sekarang <= $this->TanggalSelesaiPromosi
        ) {
            return $this->Harga - ($this->Harga * $this->DiskonPersen / 100);
        }

        return $this->Harga;
    }
}
