<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table = 'detail_penjualan';

    protected $primaryKey = 'DetailID';

// App/Models/DetailPenjualan.php
protected $fillable = [
    'PenjualanID',
    'ProdukID',
    'JumlahProduk',
    'Subtotal',
    'Harga', // baru
    'DiskonPromoNominal', // baru
    'DiskonPromoPersen', // baru
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
