<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';

    protected $primaryKey = 'ProdukID';

    protected $fillable = [
        'NamaProduk',
        'Harga',
        'Stok',
        'Satuan',
        'Gambar',
        'Promosi',
        'DiskonPersen',
        'TanggalMulaiPromosi',
        'TanggalSelesaiPromosi',
    ];

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
