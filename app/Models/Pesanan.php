<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table      = 'pesanan';
    protected $primaryKey = 'idpesanan';

    protected $fillable = [
        'idvendor',      
        'nama',
        'kode_pesanan',
        'total',
        'metode_bayar',
        'status_bayar',
        'midtrans_token',
        'timestamp',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }
}