<?php

namespace App\Models;

use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';

    protected $primaryKey = 'PenjualanID';

    protected $fillable = [
        'PenjualanID',
        'TanggalPenjualan',
        'TotalHarga',
        'user_id',
        'PelangganID',
        'NamaPelanggan',
        'UangTunai',
        'Kembalian',
        'MetodePembayaran',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'PenjualanID');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'PelangganID');
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
