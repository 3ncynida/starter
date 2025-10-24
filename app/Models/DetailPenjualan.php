<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'DetailID';
    protected $fillable = [
        'PenjualanID',
        'produk_nama',
        'produk_harga_asli',
        'produk_harga_jual',
        'diskon_promo_persen',
        'diskon_promo_nominal',
        'jumlah',
        'subtotal',
    ];


    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'PenjualanID', 'PenjualanID');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'ProdukID', 'ProdukID');
    }
}
