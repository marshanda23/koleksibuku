<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\KantinCustomerController;
use App\Http\Controllers\KantinVendorController;
use App\Http\Controllers\KantinPaymentController;
use App\Http\Controllers\KantinQrController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KunjunganTokoController;
use App\Http\Controllers\AntrianController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get('otp-verification', [LoginController::class, 'otpView'])->name('otp.view');
Route::post('otp-verification', [LoginController::class, 'verifyOtp'])->name('otp.verify');

// KANTIN - CUSTOMER (tanpa login)
Route::prefix('kantin')->name('kantin.')->group(function () {
    Route::get('/order', [KantinCustomerController::class, 'index'])->name('order');
    Route::get('/menu/{idvendor}', [KantinCustomerController::class, 'getMenu'])->name('menu');
    Route::post('/pesan', [KantinCustomerController::class, 'pesan'])->name('pesan');
    
    Route::get('/qr/{idpesanan}', [KantinQrController::class, 'generate'])->name('qr');
     Route::get('/riwayat', [KantinCustomerController::class, 'riwayat'])->name('riwayat');
     Route::get('/riwayat/{nama}', [KantinCustomerController::class, 'riwayatByNama'])->name('riwayat.nama');
});

// KANTIN - PAYMENT 
Route::prefix('kantin/payment')->name('kantin.payment.')->group(function () {
    Route::post('/token', [KantinPaymentController::class, 'createToken'])->name('token');
    Route::post('/notification', [KantinPaymentController::class, 'notification'])->name('notification');
    Route::get('/status/{idpesanan}', [KantinPaymentController::class, 'cekStatus'])->name('status');
     Route::post('/update-status',      [KantinPaymentController::class, 'updateStatus'])->name('update-status');
});
// KANTIN - VENDOR
Route::prefix('kantin/vendor')->name('kantin.vendor.')->group(function () {
    Route::get('/login', [KantinVendorController::class, 'loginView'])->name('login');
    Route::post('/login', [KantinVendorController::class, 'login'])->name('login.post');
    Route::get('/logout', [KantinVendorController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [KantinVendorController::class, 'dashboard'])->name('dashboard');
    Route::get('/menu', [KantinVendorController::class, 'menuIndex'])->name('menu');
    Route::post('/menu', [KantinVendorController::class, 'menuStore'])->name('menu.store');
    Route::delete('/menu/{id}', [KantinVendorController::class, 'menuDestroy'])->name('menu.destroy');
    Route::get('/menu/{id}/edit', [KantinVendorController::class, 'menuEdit'])->name('menu.edit');
    Route::put('/menu/{id}', [KantinVendorController::class, 'menuUpdate'])->name('menu.update');

    Route::get('/register', [KantinVendorController::class, 'registerView'])->name('register');
    Route::post('/register', [KantinVendorController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [KantinVendorController::class, 'forgotView'])->name('forgot');
    Route::post('/forgot-password', [KantinVendorController::class, 'forgotReset'])->name('forgot.post');
     Route::get('/scan', [KantinVendorController::class, 'scanView'])->name('scan');
    Route::post('/scan', [KantinVendorController::class, 'scanProcess'])->name('scan.process');

});
Route::prefix('antrian')->name('antrian.')->group(function () {
    Route::get('/guest', [AntrianController::class, 'guest'])->name('guest');
    Route::post('/daftar', [AntrianController::class, 'daftar'])->name('daftar');
    Route::get('/tiket/{id}', [AntrianController::class, 'tiket'])->name('tiket');
    Route::get('/papan', [AntrianController::class, 'papan'])->name('papan');
    Route::get('/stream', [AntrianController::class, 'stream'])
        ->name('stream')
        ->withoutMiddleware([
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);
});

// ROUTE DENGAN AUTH
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');

    Route::get('/barang-js', function () {
        return view('barang_js');
    })->name('barang.js');

    Route::get('/barang-js-datatables', function () {
        return view('barang_js_datatable');
    })->name('barang.js.datatables');

    Route::get('/select-kota', function () {
        return view('select.index');
    })->name('select.index');

    Route::resource('kategori', KategoriController::class);
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');

    Route::resource('buku', BukuController::class);
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');

    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/scan-barcode', [BarangController::class, 'scanIndex'])->name('barang.scan.index');
    Route::get('/barang/scan/{id_barang}', [BarangController::class, 'scanBarcode'])->name('barang.scan');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        Route::post('/cari-barang', [POSController::class, 'cariBarang'])->name('cari');
        Route::post('/bayar', [POSController::class, 'bayar'])->name('bayar');
    });

    Route::prefix('wilayah')->name('wilayah.')->group(function () {
        Route::get('/', [WilayahController::class, 'index'])->name('index');
        Route::get('/kota/{provinsi}', [WilayahController::class, 'getKota']);
        Route::get('/kecamatan/{kota}', [WilayahController::class, 'getKecamatan']);
        Route::get('/kelurahan/{kecamatan}', [WilayahController::class, 'getKelurahan']);
    });
    Route::prefix('customer')->name('customer.')->group(function () {
    // URL: /customer
    Route::get('/', [CustomerController::class, 'index'])->name('index');

    // URL: /customer/tambah1
    Route::get('/tambah1', [CustomerController::class, 'tambah1'])->name('tambah1');
    Route::post('/tambah1', [CustomerController::class, 'store1'])->name('store1');

    // URL: /customer/tambah2
    Route::get('/tambah2', [CustomerController::class, 'tambah2'])->name('tambah2');
    Route::post('/tambah2', [CustomerController::class, 'store2'])->name('store2');

    Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CustomerController::class, 'update'])->name('update');

    Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
});
    Route::post('/barang/cetak-tag', [BarangController::class, 'cetakTag'])->name('barang.cetak_tag');
    Route::get('/download-sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('/download-undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');

Route::prefix('kunjungan-toko')->group(function () {
    Route::get('/',               [KunjunganTokoController::class, 'index'])->name('kunjungan.index');
    Route::post('/simpan-toko',   [KunjunganTokoController::class, 'simpanToko'])->name('kunjungan.simpanToko');
    Route::get('/get-toko/{barcode}', [KunjunganTokoController::class, 'getToko']);
    Route::post('/proses',        [KunjunganTokoController::class, 'prosesKunjungan'])->name('kunjungan.proses');
});
Route::prefix('antrian')->name('antrian.')->group(function () {
        Route::get('/admin', [AntrianController::class, 'admin'])->name('admin');
        Route::post('/panggil', [AntrianController::class, 'panggil'])->name('panggil');
        Route::post('/terlambat', [AntrianController::class, 'terlambat'])->name('terlambat');
        Route::post('/panggil-terlambat', [AntrianController::class, 'panggilTerlambat'])->name('panggil.terlambat');
    });
});