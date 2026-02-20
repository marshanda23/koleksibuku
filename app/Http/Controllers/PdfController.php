<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function sertifikat()
    {
        $data = [
            'nama' => 'SALSABILLA',
            'nomor' => 'CERT/' . date('Ymd') . '/VOKASI',
            'tanggal' => date('d F Y')
        ];

        $pdf = Pdf::loadView('pdf.sertifikat', $data)->setPaper('a4', 'landscape');
        return $pdf->stream('Sertifikat_A4_Landscape.pdf');
    }

    public function undangan()
    {
        $data = [
            'nomor_surat' => '102/UN3/PK/2026',
            'perihal' => 'Undangan Rapat Fakultas',
            'tanggal' => date('d F Y'),
            'penerima' => 'Mahasiswa Vokasi Airlangga'
        ];

        $pdf = Pdf::loadView('pdf.undangan', $data)->setPaper('a4', 'portrait');
        return $pdf->stream('Undangan_A4_Portrait.pdf');
    }
}
