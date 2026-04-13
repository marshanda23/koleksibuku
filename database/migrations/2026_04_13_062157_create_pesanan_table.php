<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('idpesanan');
            $table->foreignId('idvendor')->constrained('vendor', 'idvendor')->onDelete('cascade');
            $table->string('nama');              
            $table->string('kode_pesanan')->unique();
            $table->decimal('total', 10, 2);
            $table->string('metode_bayar')->nullable(); 
            $table->tinyInteger('status_bayar')->default(0); 
            $table->string('midtrans_token')->nullable();
            $table->timestamp('timestamp')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};