<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KantinCustomerController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('kantin.customer.index', compact('vendors'));
    }

    public function getMenu($idvendor)
    {
        $menu = Menu::where('idvendor', $idvendor)->get();
        return response()->json($menu);
    }

    public function pesan(Request $request)
    {
        $request->validate([
            'idvendor'     => 'required|exists:vendor,idvendor',
            'items'        => 'required|array|min:1',
            'metode_bayar' => 'required|in:virtual_account,qris',
        ]);

        DB::beginTransaction();
        try {
            $totalPesanan = Pesanan::count();
            $namaCustomer = 'Guest_' . str_pad($totalPesanan + 1, 7, '0', STR_PAD_LEFT);

            $total          = 0;
            $validatedItems = [];
            foreach ($request->items as $item) {
                $menu     = Menu::findOrFail($item['idmenu']);
                $subtotal = $menu->harga * $item['jumlah'];
                $total   += $subtotal;

                $validatedItems[] = [
                    'idmenu'   => $menu->idmenu,
                    'jumlah'   => $item['jumlah'],
                    'harga'    => $menu->harga,
                    'subtotal' => $subtotal,
                ];
            }

            $kodePesanan = 'ORD-' . strtoupper(uniqid());

            $pesanan = Pesanan::create([
                'idvendor'     => $request->idvendor,
                'nama'         => $namaCustomer,
                'total'        => $total,
                'metode_bayar' => $request->metode_bayar,
                'status_bayar' => 0,
                'kode_pesanan' => $kodePesanan,
                'timestamp'    => now(),
            ]);

            foreach ($validatedItems as $item) {
                DetailPesanan::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu'    => $item['idmenu'],
                    'jumlah'    => $item['jumlah'],
                    'harga'     => $item['harga'],
                    'subtotal'  => $item['subtotal'],
                    'timestamp' => now(),
                ]);
            }

            DB::commit();

            session(['customer_nama' => $namaCustomer]);

            return response()->json([
                'success'      => true,
                'idpesanan'    => $pesanan->idpesanan,
                'kode_pesanan' => $kodePesanan,
                'nama'         => $namaCustomer,
                'total'        => $total,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function riwayat()
    {
        $nama = session('customer_nama');

        if (!$nama) {
            return redirect()->route('kantin.order')
                ->with('error', 'Silahkan lakukan pemesanan terlebih dahulu.');
        }

        $pesananList = Pesanan::with(['detailPesanan.menu'])
            ->where('nama', $nama)
            ->orderByDesc('created_at')
            ->get();

        return view('kantin.customer.riwayat', compact('pesananList', 'nama'));
    }
public function riwayatByNama($nama)
    {
        session(['customer_nama' => $nama]);
 
        $pesananList = Pesanan::with(['detailPesanan.menu'])
            ->where('nama', $nama)
            ->orderByDesc('timestamp')
            ->get();
 
        return view('kantin.customer.riwayat', compact('pesananList', 'nama'));
    }
}