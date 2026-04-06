<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table      = 'pesanan';
    protected $primaryKey = 'idpesanan';
    public $timestamps    = false;

    protected $fillable = [
        'nama',
        'total',
        'metode_bayar',
        'status_bayar',
        'kode_pesanan',
        'midtrans_token',
        'midtrans_order_id',
        'timestamp',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
    ];

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'idpesanan', 'idpesanan');
    }
}