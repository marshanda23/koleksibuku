<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AntrianController;

Route::get('/antrian/stream', [AntrianController::class, 'stream']);