<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Toko;
use App\Models\Kunjungan;

class KunjunganTokoController extends Controller
{
    public function index()
    {
        $tokoList = Toko::all();
        return view('kunjungan_toko.index', compact('tokoList'));
    }

    public function simpanToko(Request $request)
    {
        $request->validate([
            'nama_toko' => 'required|string',
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'accuracy'  => 'required|numeric',
        ]);

        $barcode = 'TK-' . strtoupper(substr(uniqid(), -5));

        Toko::create([
            'barcode'   => $barcode,
            'nama_toko' => $request->nama_toko,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy'  => $request->accuracy,
        ]);

        return back()->with('success', 'Toko berhasil ditambahkan!');
    }

    public function getToko($barcode)
    {
        $toko = Toko::where('barcode', $barcode)->first();
        if (!$toko) return response()->json(['error' => 'Toko tidak ditemukan'], 404);
        return response()->json($toko);
    }

   public function prosesKunjungan(Request $request)
{
    if (!$request->lat_sales || !$request->lat_toko) {
        return response()->json(['error' => 'Data lokasi tidak lengkap'], 400);
    }
        $threshold = 300; 

        $jarak = $this->haversine(
            $request->lat_toko, $request->lng_toko,
            $request->lat_sales, $request->lng_sales
        );

        $threshold_efektif = $threshold + $request->acc_toko + $request->acc_sales;
        $status = $jarak <= $threshold_efektif ? 'DITERIMA' : 'DITOLAK';

        Kunjungan::create([
            'barcode_toko' => $request->barcode_toko,
            'nama_toko'    => $request->nama_toko,
            'lat_toko'     => $request->lat_toko,
            'lng_toko'     => $request->lng_toko,
            'acc_toko'     => $request->acc_toko,
            'lat_sales'    => $request->lat_sales,
            'lng_sales'    => $request->lng_sales,
            'acc_sales'    => $request->acc_sales,
            'jarak_meter'  => round($jarak, 2),
            'status'       => $status,
        ]);

        return response()->json([
            'status'             => $status,
            'jarak_meter'        => round($jarak, 2),
            'threshold_efektif'  => round($threshold_efektif, 2),
        ]);
    }

    // Formula Haversine
    private function haversine($lat1, $lng1, $lat2, $lng2)
    {
        $R = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng/2) * sin($dLng/2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $R * $c;
    }
}