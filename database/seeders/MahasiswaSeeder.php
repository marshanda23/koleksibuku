<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
    ['nama' => 'Marshanda Hadi S',       'nim' => '20221234', 'serial_nfc' => '04:AB:CD:EF:12:34:01'],
    ['nama' => 'Salsyabilla Nurul Shifa','nim' => '20221198', 'serial_nfc' => '04:AB:CD:EF:12:34:02'],
    ['nama' => 'Annaura Salsabilla',     'nim' => '20221301', 'serial_nfc' => '04:AB:CD:EF:12:34:03'],
    ['nama' => 'Ciza Aferta',            'nim' => '20221410', 'serial_nfc' => '04:AB:CD:EF:12:34:04'],
    ['nama' => 'Oliver',                 'nim' => '20221512', 'serial_nfc' => '04:AB:CD:EF:12:34:05'],
];

        foreach ($data as $item) {
            Mahasiswa::create($item);
        }
    }
}
