<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Vendor extends Authenticatable
{
    protected $table      = 'vendor';
    protected $primaryKey = 'idvendor';
    public $timestamps    = false;

    protected $fillable = [
        'nama_vendor',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function menu()
    {
        return $this->hasMany(Menu::class, 'idvendor', 'idvendor');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'idvendor', 'idvendor');
    }
}