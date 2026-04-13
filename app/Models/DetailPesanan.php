<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table      = 'detail_pesanan';
    protected $primaryKey = 'iddetail';

    protected $fillable = [
        'idpesanan',
        'idmenu',
        'jumlah',
        'harga',
        'subtotal',
        'timestamp',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'idpesanan', 'idpesanan');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'idmenu', 'idmenu');
    }
}