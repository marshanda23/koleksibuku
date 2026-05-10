<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
     protected $table = 'kunjungan';
    protected $fillable = [
        'barcode_toko', 
        'nama_toko', 
        'lat_toko', 
        'lng_toko', 
        'acc_toko',
        'lat_sales', 
        'lng_sales', 
        'acc_sales', 
        'jarak_meter', 
        'status'
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }
}