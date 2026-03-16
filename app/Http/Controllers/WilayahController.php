<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{

public function index()
{
$provinces = DB::table('reg_provinces')->orderBy('name')->get();
return view('wilayah.index',compact('provinces'));
}

public function getKota($provinsi)
{
$data = DB::table('reg_regencies')
->where('province_id',$provinsi)
->orderBy('name')
->get();

return response()->json($data);
}

public function getKecamatan($kota)
{
$data = DB::table('reg_districts')
->where('regency_id',$kota)
->orderBy('name')
->get();

return response()->json($data);
}

public function getKelurahan($kecamatan)
{
$data = DB::table('reg_villages')
->where('district_id',$kecamatan)
->orderBy('name')
->get();

return response()->json($data);
}

}