<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrian', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor');
            $table->string('nama');
            $table->enum('status', ['menunggu', 'dipanggil', 'terlambat'])->default('menunggu');
            $table->string('loket')->nullable();
            $table->string('ruangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrian');
    }
};