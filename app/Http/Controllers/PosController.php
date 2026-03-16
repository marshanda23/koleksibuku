<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{

    public function index()
    {
        return view('pos.index');
    }

    public function cariBarang(Request $request)
    {

        $barang = DB::table('barang')
            ->where('id_barang', $request->kode)
            ->first();

        if ($barang) {

            return response()->json([
                'status' => 'success',
                'data' => $barang
            ]);
        }

        return response()->json([
            'status' => 'error'
        ]);
    }


   public function bayar(Request $request)
{
    try {

        $items = $request->items;
        $total = $request->total;

        $penjualan_id = DB::table('penjualan')->insertGetId([
            'total' => $total,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        foreach ($items as $item) {

            DB::table('detail_penjualan')->insert([
                'penjualan_id' => $penjualan_id,
                'kode_barang' => $item['kode'],
                'harga' => $item['harga'],
                'jumlah' => $item['jumlah'],
                'subtotal' => $item['subtotal'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

        }

        return response()->json([
            'status' => 'success'
        ]);

    } catch (\Exception $e) {

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ]);

    }
}
}

