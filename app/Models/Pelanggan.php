<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'PelangganID';
    protected $fillable = [
        'NamaPelanggan',
        'NoTelepon',
        'Alamat',
    ];

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'PelangganID', 'PelangganID');
    }
}
