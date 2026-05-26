<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('absensis', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
        $table->date('tanggal');
        $table->time('waktu');
        $table->string('serial_nfc', 50);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
