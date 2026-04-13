<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->id('idmenu');
            $table->foreignId('idvendor')->constrained('vendor', 'idvendor')->onDelete('cascade');
            $table->string('nama_menu');
            $table->decimal('harga', 10, 2);
            $table->string('path_gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};