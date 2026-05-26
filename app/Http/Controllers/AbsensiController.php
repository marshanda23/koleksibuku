<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use App\Models\Absensi;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $logHariIni = Absensi::with('mahasiswa')
            ->whereDate('tanggal', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->get();

        $totalMahasiswa = Mahasiswa::count();

        $view = auth()->check() ? 'absensi.index_admin' : 'absensi.index';

        return view($view, compact('logHariIni', 'totalMahasiswa'));
    }

    public function scan(Request $request)
    {
        $request->validate([
            'serial_nfc' => 'required|string',
        ]);

        $serial = $request->serial_nfc;
        $mahasiswa = Mahasiswa::where('serial_nfc', $serial)->first();

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Kartu tidak terdaftar.',
            ], 404);
        }

        $sudahAbsen = Absensi::where('mahasiswa_id', $mahasiswa->id)
            ->whereDate('tanggal', Carbon::today())
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'status'    => 'duplikat',
                'pesan'     => $mahasiswa->nama . ' sudah absen hari ini.',
                'mahasiswa' => $mahasiswa,
            ]);
        }

        $absensi = Absensi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'tanggal'      => Carbon::today(),
            'waktu'        => Carbon::now()->format('H:i:s'),
            'serial_nfc'   => $serial,
        ]);

        return response()->json([
            'status'    => 'berhasil',
            'pesan'     => 'Absensi berhasil dicatat.',
            'mahasiswa' => $mahasiswa,
            'waktu'     => $absensi->waktu,
        ]);
    }

    public function mahasiswa()
    {
        $mahasiswas = Mahasiswa::orderBy('nama')->get();
        return view('absensi.mahasiswa', compact('mahasiswas'));
    }

    public function daftarkanKartu(Request $request)
    {
        $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'serial_nfc'   => 'required|string',
        ]);

        $sudahAda = Mahasiswa::where('serial_nfc', $request->serial_nfc)
            ->where('id', '!=', $request->mahasiswa_id)
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Kartu ini sudah terdaftar ke mahasiswa lain.',
            ]);
        }

        $mahasiswa = Mahasiswa::find($request->mahasiswa_id);
        $mahasiswa->serial_nfc = $request->serial_nfc;
        $mahasiswa->save();

        return response()->json([
            'status'    => 'berhasil',
            'pesan'     => 'Kartu berhasil didaftarkan ke ' . $mahasiswa->nama,
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function tambahMahasiswa(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nim'  => 'required|string|max:50',
        ]);

        $sudahAda = Mahasiswa::where('nim', $request->nim)->exists();
        if ($sudahAda) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'NIM ' . $request->nim . ' sudah terdaftar.',
            ]);
        }

        $mahasiswa = Mahasiswa::create([
    'nama'       => $request->nama,
    'nim'        => $request->nim,
    'serial_nfc' => null, 
    'serial_nfc' => '',    
]);

        return response()->json([
            'status'    => 'berhasil',
            'pesan'     => $mahasiswa->nama . ' berhasil ditambahkan.',
            'mahasiswa' => $mahasiswa,
        ]);
    }

    public function hapusMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::find($id);

        if (!$mahasiswa) {
            return response()->json([
                'status' => 'gagal',
                'pesan'  => 'Mahasiswa tidak ditemukan.',
            ], 404);
        }
        
        Absensi::where('mahasiswa_id', $id)->delete();
        $mahasiswa->delete();

        return response()->json([
            'status' => 'berhasil',
            'pesan'  => 'Mahasiswa berhasil dihapus.',
        ]);
    }
}