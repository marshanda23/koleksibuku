<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PdfController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

Route::get('otp-verification', [LoginController::class, 'otpView'])->name('otp.view');
Route::post('otp-verification', [LoginController::class, 'verifyOtp'])->name('otp.verify');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::post('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
    
    Route::resource('kategori' , KategoriController::class);
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    
    Route::resource('buku', BukuController::class);
    Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');
    Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');
    Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');
    Route::post('/barang/cetak-tag', [BarangController::class, 'cetakTag'])->name('barang.cetak_tag');
    Route::get('/download-sertifikat', [PdfController::class, 'sertifikat'])->name('pdf.sertifikat');
    Route::get('/download-undangan', [PdfController::class, 'undangan'])->name('pdf.undangan');
});