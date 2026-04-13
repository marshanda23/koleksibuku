<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $fillable = [
        'nama', 'alamat', 'provinsi', 'kota',
        'kecamatan', 'kodepos_kelurahan', 'kodepos',
        'foto_blob', 'foto_path',
    ];
}