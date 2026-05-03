<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KantinVendorController extends Controller
{
    public function loginView()
    {
        if (session('vendor_id')) {
            return redirect('/kantin/vendor/dashboard');
        }
        return view('kantin.vendor.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $vendor = Vendor::where('username', $request->username)->first();

        if (!$vendor || !Hash::check($request->password, $vendor->password)) {
            return back()->withErrors(['login' => 'Username atau password salah!']);
        }

        session([
            'vendor_id'   => $vendor->idvendor,
            'vendor_nama' => $vendor->nama_vendor,
        ]);

        return redirect('/kantin/vendor/dashboard');
    }

    public function logout()
    {
        session()->forget(['vendor_id', 'vendor_nama']);
        return redirect('/kantin/vendor/login');
    }

    public function dashboard()
{
    if (!session('vendor_id')) {
        return redirect('/kantin/vendor/login');
    }
 
    $pesanan = Pesanan::with('details.menu')
        ->where('status_bayar', 1)
        ->orderBy('idpesanan', 'desc')
        ->get();
 
    $pesananJson = $pesanan->map(function($p) {
        return [
            'idpesanan'    => $p->idpesanan,
            'kode_pesanan' => $p->kode_pesanan,
            'nama'         => $p->nama,
            'total'        => $p->total,
            'metode_bayar' => $p->metode_bayar,
            'timestamp'    => $p->timestamp,
            'details'      => $p->details->map(function($d) {
                return [
                    'nama_menu' => $d->menu->nama_menu ?? '-',
                    'jumlah'    => $d->jumlah,
                    'harga'     => $d->harga,
                    'subtotal'  => $d->subtotal,
                ];
            }),
        ];
    });
 
    return view('kantin.vendor.dashboard', compact('pesanan', 'pesananJson'));
}
 

    public function menuIndex()
    {
        if (!session('vendor_id')) {
            return redirect('/kantin/vendor/login');
        }

        $menus = Menu::where('idvendor', session('vendor_id'))->get();
        return view('kantin.vendor.menu', compact('menus'));
    }

    public function menuStore(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga'     => 'required|numeric',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menu', 'public');
        }

        Menu::create([
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'path_gambar' => $path,
            'idvendor'    => session('vendor_id'),
        ]);

        return back()->with('success', 'Menu berhasil ditambahkan!');
    }

    public function menuDestroy($id)
    {
        Menu::where('idmenu', $id)
            ->where('idvendor', session('vendor_id'))
            ->delete();

        return back()->with('success', 'Menu berhasil dihapus!');
    }

    public function menuEdit($id)
    {
        $menu = Menu::where('idmenu', $id)
                    ->where('idvendor', session('vendor_id'))
                    ->firstOrFail();
        return view('kantin.vendor.menu_edit', compact('menu'));
    }

    public function menuUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required',
            'harga'     => 'required|numeric',
        ]);

        $menu = Menu::where('idmenu', $id)
                    ->where('idvendor', session('vendor_id'))
                    ->firstOrFail();

        $menu->nama_menu = $request->nama_menu;
        $menu->harga     = $request->harga;

        if ($request->hasFile('gambar')) {
            $menu->path_gambar = $request->file('gambar')->store('menu', 'public');
        }

        $menu->save();

        return back()->with('success', 'Menu berhasil diperbarui!');
    }

    public function registerView()
    {
        if (session('vendor_id')) {
            return redirect('/kantin/vendor/dashboard');
        }
        return view('kantin.vendor.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required',
            'username'    => 'required|unique:vendor,username',
            'password'    => 'required|min:6|confirmed',
        ]);

        Vendor::create([
            'nama_vendor' => $request->nama_vendor,
            'username'    => $request->username,
            'password'    => Hash::make($request->password),
        ]);

        return redirect()->route('kantin.vendor.login')
                         ->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function forgotView()
    {
        if (session('vendor_id')) {
            return redirect('/kantin/vendor/dashboard');
        }
        return view('kantin.vendor.forgot');
    }

    public function forgotReset(Request $request)
    {
        $request->validate([
            'username'    => 'required',
            'nama_vendor' => 'required',
            'password'    => 'required|min:6|confirmed',
        ]);

        $vendor = Vendor::where('username', $request->username)
                        ->where('nama_vendor', $request->nama_vendor)
                        ->first();

        if (!$vendor) {
            return back()->withErrors(['verify' => 'Username atau nama vendor tidak cocok!']);
        }

        $vendor->password = Hash::make($request->password);
        $vendor->save();

        return redirect()->route('kantin.vendor.login')
                         ->with('success', 'Password berhasil direset! Silakan login.');
    }

    public function scanView()
    {
        if (!session('vendor_id')) {
            return redirect()->route('kantin.vendor.login');
        }

        $vendorNama = session('vendor_nama');
        return view('kantin.vendor.scan', compact('vendorNama'));
    }

    public function scanProcess(Request $request)
    {
        $request->validate(['idpesanan' => 'required']);

        $vendorId = session('vendor_id');
        if (!$vendorId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $idpesanan = trim($request->idpesanan);

        $pesanan = Pesanan::with([
                'detailPesanan' => function ($q) use ($vendorId) {
                    $q->whereHas('menu', function ($q2) use ($vendorId) {
                        $q2->where('idvendor', $vendorId);
                    })->with('menu');
                }
            ])
            ->where('idpesanan', $idpesanan)
            ->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.'
            ]);
        }

        if ($pesanan->detailPesanan->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan ini tidak memiliki menu dari vendor Anda.'
            ]);
        }

        $statusLabel = match ((int) $pesanan->status_bayar) {
            1       => 'paid',
            2       => 'failed',
            default => 'pending',
        };

        return response()->json([
            'success'       => true,
            'kode_pesanan'  => $pesanan->kode_pesanan,
            'nama_customer' => $pesanan->nama,
            'status_bayar'  => $statusLabel,
            'total'         => $pesanan->total,
            'created_at'    => \Carbon\Carbon::parse($pesanan->timestamp)->format('d M Y H:i'),
            'items'         => $pesanan->detailPesanan->map(function ($d) {
                return [
                    'nama_menu' => $d->menu->nama_menu,
                    'harga'     => $d->menu->harga,
                    'jumlah'    => $d->jumlah,
                    'subtotal'  => $d->menu->harga * $d->jumlah,
                ];
            }),
        ]);
    }
}