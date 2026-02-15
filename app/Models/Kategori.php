<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = ['nama_kategori'];

    // TAMBAHKAN FUNGSI INI
    public function buku()
    {
        return $this->hasMany(Buku::class);
    }
}