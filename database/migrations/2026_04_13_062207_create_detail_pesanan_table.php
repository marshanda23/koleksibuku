<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('iddetail');
            $table->foreignId('idpesanan')->constrained('pesanan', 'idpesanan')->onDelete('cascade');
            $table->foreignId('idmenu')->constrained('menu', 'idmenu')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamp('timestamp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};