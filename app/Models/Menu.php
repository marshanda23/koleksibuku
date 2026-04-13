<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table      = 'menu';
    protected $primaryKey = 'idmenu';

    protected $fillable = [
        'idvendor',
        'nama_menu',
        'harga',
        'path_gambar',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'idvendor', 'idvendor');
    }

    public function details()
    {
        return $this->hasMany(DetailPesanan::class, 'idmenu', 'idmenu');
    }
}