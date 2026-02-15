<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ // <--- Kurung kurawal buka ini WAJIB ada

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom foto
            $table->string('foto')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }

}; // <--- Kurung kurawal tutup dan titik koma ini juga WAJIB