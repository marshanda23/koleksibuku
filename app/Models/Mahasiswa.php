<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $fillable = ['nama', 'nim', 'serial_nfc'];

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}