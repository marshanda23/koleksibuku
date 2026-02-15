<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
   
    Route::resource('kategori' , KategoriController::class);
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    
    
   Route::resource('buku', BukuController::class);
Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
});