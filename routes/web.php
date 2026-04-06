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
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get('otp-verification', [LoginController::class, 'otpView'])->name('otp.view');
Route::post('otp-verification', [LoginController::class, 'verifyOtp'])->name('otp.verify');

// =============================================
// KANTIN - CUSTOMER (tanpa login)
// =============================================
Route::prefix('kantin')->name('kantin.')->group(function () {
    Route::get('/order', [KantinCustomerController::class, 'index'])->name('order');
    Route::get('/menu/{idvendor}', [KantinCustomerController::class, 'getMenu'])->name('menu');
    Route::post('/pesan', [KantinCustomerController::class, 'pesan'])->name('pesan');
});

// =============================================
// KANTIN - PAYMENT (webhook tidak perlu auth)
// =============================================
Route::prefix('kantin/payment')->name('kantin.payment.')->group(function () {
    Route::post('/token', [KantinPaymentController::class, 'createToken'])->name('token');
    Route::post('/notification', [KantinPaymentController::class, 'notification'])->name('notification');
    Route::get('/status/{idpesanan}', [KantinPaymentController::class, 'cekStatus'])->name('status');
     Route::post('/update-status',      [KantinPaymentController::class, 'updateStatus'])->name('update-status');
});

// =============================================
// KANTIN - VENDOR (session sendiri)
// =============================================
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

    // Register Vendor 
    Route::get('/register', [KantinVendorController::class, 'registerView'])->name('register');
    Route::post('/register', [KantinVendorController::class, 'register'])->name('register.post');

    // Forgot Password 
    Route::get('/forgot-password', [KantinVendorController::class, 'forgotView'])->name('forgot');
    Route::post('/forgot-password', [KantinVendorController::class, 'forgotReset'])->name('forgot.post');
});

// =============================================
// ROUTE DENGAN AUTH
// =============================================
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

    Route::post('/barang/cetak-tag', [BarangController::class, 'cetakTag'])->name('barang.cetak_tag');
    Route::get('/download-sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('/download-undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');
});