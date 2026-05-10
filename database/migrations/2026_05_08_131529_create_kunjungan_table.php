<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->string('barcode_toko');
            $table->string('nama_toko');
            $table->decimal('lat_toko', 10, 7);
            $table->decimal('lng_toko', 10, 7);
            $table->float('acc_toko');
            $table->decimal('lat_sales', 10, 7);
            $table->decimal('lng_sales', 10, 7);
            $table->float('acc_sales');
            $table->float('jarak_meter');
            $table->string('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};