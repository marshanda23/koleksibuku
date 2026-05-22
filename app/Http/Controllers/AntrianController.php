<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AntrianController extends Controller
{

    public function guest()
    {
        return view('antrian.guest');
    }

    public function admin()
    {
        return view('antrian.admin');
    }

    public function papan()
    {
        return view('antrian.papan');
    }

    public function tiket($id)
    {
        $antrian = Cache::get('antrian_list', []);
        $tiket   = collect($antrian)->firstWhere('id', (int) $id);

        // Hitung estimasi waktu tunggu
        $menunggu = collect($antrian)->filter(fn($i) => $i['status'] === 'menunggu');
        $posisi   = $menunggu->search(fn($i) => $i['id'] === (int) $id);
        $estimasi = ($posisi !== false) ? ($posisi + 1) * 5 : null; // estimasi 5 menit per orang

        return view('antrian.tiket', compact('tiket', 'estimasi'));
    }


    public function daftar(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100']);

        $antrian = Cache::get('antrian_list', []);
        $counter = Cache::get('antrian_counter', 0) + 1;

        $data = [
            'id'     => $counter,
            'nomor'  => $counter,
            'nama'   => $request->nama,
            'status' => 'menunggu',
            'loket'  => null,
            'ruangan' => null,
            'waktu_daftar' => now()->format('H:i:s'),
        ];

        $antrian[] = $data;
        Cache::put('antrian_list', $antrian);
        Cache::put('antrian_counter', $counter);

        DB::table('antrian')->insert([
            'nomor'        => $counter,
            'nama'         => $request->nama,
            'status'       => 'menunggu',
            'loket'        => null,
            'ruangan'      => null,
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);

        return redirect()->route('antrian.tiket', $counter);
    }

    public function panggil(Request $request)
    {
        $ruangan = $request->ruangan ?? 'Ruang 1';
        $loket   = $request->loket   ?? 'Loket 1';
        $antrian = Cache::get('antrian_list', []);

        foreach ($antrian as &$item) {
            if ($item['status'] === 'menunggu') {
                $item['status']  = 'dipanggil';
                $item['ruangan'] = $ruangan;
                $item['loket']   = $loket;
                Cache::put('antrian_sekarang', $item);

                DB::table('antrian')
                    ->where('nomor', $item['nomor'])
                    ->update([
                        'status'     => 'dipanggil',
                        'ruangan'    => $ruangan,
                        'loket'      => $loket,
                        'updated_at' => now(),
                    ]);
                break;
            }
        }

        Cache::put('antrian_list', $antrian);
        return response()->json(['success' => true]);
    }

    public function terlambat(Request $request)
    {
        $antrian = Cache::get('antrian_list', []);
        $id      = $request->id;

        foreach ($antrian as &$item) {
            if ($item['id'] == $id) {
                $item['status'] = 'terlambat';

                DB::table('antrian')
                    ->where('nomor', $item['nomor'])
                    ->update(['status' => 'terlambat', 'updated_at' => now()]);
                break;
            }
        }

        Cache::put('antrian_list', $antrian);
        return response()->json(['success' => true]);
    }

    public function panggilTerlambat(Request $request)
    {
        $ruangan = $request->ruangan ?? 'Ruang 1';
        $loket   = $request->loket   ?? 'Loket 1';
        $antrian = Cache::get('antrian_list', []);
        $id      = $request->id;

        foreach ($antrian as &$item) {
            if ($item['id'] == $id && $item['status'] === 'terlambat') {
                $item['status']  = 'dipanggil';
                $item['ruangan'] = $ruangan;
                $item['loket']   = $loket;
                Cache::put('antrian_sekarang', $item);

                DB::table('antrian')
                    ->where('nomor', $item['nomor'])
                    ->update([
                        'status'     => 'dipanggil',
                        'ruangan'    => $ruangan,
                        'loket'      => $loket,
                        'updated_at' => now(),
                    ]);
                break;
            }
        }

        Cache::put('antrian_list', $antrian);
        return response()->json(['success' => true]);
    }

    public function stream()
    {
        set_time_limit(0);

        $headers = [
            'Content-Type'                => 'text/event-stream',
            'Cache-Control'               => 'no-cache',
            'Connection'                  => 'keep-alive',
            'X-Accel-Buffering'           => 'no',
            'Access-Control-Allow-Origin' => '*',
        ];

        return response()->stream(function () {

            while (ob_get_level() > 0) {
                ob_end_clean();
            }
            ob_implicit_flush(true);

            echo ": connected\n\n";
            flush();

            while (true) {
                $list     = Cache::get('antrian_list', []);
                $sekarang = Cache::get('antrian_sekarang', null);

                $menunggu = array_values(array_filter($list, fn($i) => $i['status'] === 'menunggu'));
                foreach ($menunggu as $idx => &$item) {
                    $item['estimasi_menit'] = ($idx + 1) * 5;
                }

                $data = [
                    'list'     => $list,
                    'sekarang' => $sekarang,
                ];

                echo "event: queue-update\n";
                echo "data: " . json_encode($data) . "\n\n";

                ob_flush();
                flush();

                if (connection_aborted()) break;

                sleep(2);
            }

        }, 200, $headers);
    }
}