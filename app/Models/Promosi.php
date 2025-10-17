<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promosi extends Model
{
    use HasFactory;

    protected $table = 'promosi';
    protected $primaryKey = 'PromosiID';
    public $timestamps = true;

    protected $fillable = [
        'ProdukID',
        'NamaPromosi',
        'TipeDiskon', // persen / nominal
        'NilaiDiskon',
        'TanggalMulai',
        'TanggalSelesai',
        'Status', // aktif / nonaktif
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ProdukID', 'ProdukID');
    }
}
