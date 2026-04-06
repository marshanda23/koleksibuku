<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KantinVendorController extends Controller
{
    // Halaman login vendor
    public function loginView()
    {
        if (session('vendor_id')) {
            return redirect('/kantin/vendor/dashboard');
        }
        return view('kantin.vendor.login');
    }

    // Proses login vendor
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

    // Logout vendor
    public function logout()
    {
        session()->forget(['vendor_id', 'vendor_nama']);
        return redirect('/kantin/vendor/login');
    }

    // Dashboard: pesanan lunas
    public function dashboard()
    {
        if (!session('vendor_id')) {
            return redirect('/kantin/vendor/login');
        }

        $pesanan = Pesanan::with('details.menu')
            ->where('status_bayar', 1)
            ->orderBy('idpesanan', 'desc')
            ->get();

        return view('kantin.vendor.dashboard', compact('pesanan'));
    }

    // Halaman kelola menu
    public function menuIndex()
    {
        if (!session('vendor_id')) {
            return redirect('/kantin/vendor/login');
        }

        $menus = Menu::where('idvendor', session('vendor_id'))->get();
        return view('kantin.vendor.menu', compact('menus'));
    }

    // Simpan menu baru
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

    // Hapus menu
    public function menuDestroy($id)
    {
        Menu::where('idmenu', $id)
            ->where('idvendor', session('vendor_id'))
            ->delete();

        return back()->with('success', 'Menu berhasil dihapus!');
    }

    // Update menu
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

    // =============================================
    // REGISTER VENDOR
    // =============================================

    // Halaman register vendor
    public function registerView()
    {
        if (session('vendor_id')) {
            return redirect('/kantin/vendor/dashboard');
        }
        return view('kantin.vendor.register');
    }

    // Proses register vendor
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

    // =============================================
    // FORGOT PASSWORD VENDOR
    // =============================================

    // Halaman forgot password
    public function forgotView()
    {
        if (session('vendor_id')) {
            return redirect('/kantin/vendor/dashboard');
        }
        return view('kantin.vendor.forgot');
    }

    // Proses reset password
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
}