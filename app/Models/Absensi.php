<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $fillable = ['mahasiswa_id', 'tanggal', 'waktu', 'serial_nfc'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
