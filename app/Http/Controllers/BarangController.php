<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::orderBy('id_barang', 'asc')->get();
        return view('barang.index', compact('barang'))->with('barang_edit', null);
    }

   public function store(Request $request)
{
    $request->validate([
        'nama'  => 'required|string|max:50',
        'harga' => 'required|integer|min:0',
    ]);

    $maxAttempts = 5;
    $attempt = 0;

    while ($attempt < $maxAttempts) {
        try {
            
            DB::transaction(function () use ($request) {
                $prefix = now()->format('ymd');
                
                $last = Barang::where('id_barang', 'like', $prefix . '%')
                    ->orderBy('id_barang', 'desc')
                    ->lockForUpdate() 
                    ->first();

                $seq = $last ? (intval(substr($last->id_barang, 6)) + 1) : 1;
                $newId = $prefix . str_pad($seq, 2, '0', STR_PAD_LEFT);
                
                Log::info("Mencoba membuat ID: $newId");

                // Simpan data
                Barang::create([
                    'id_barang' => $newId,
                    'nama'      => $request->nama,
                    'harga'     => $request->harga,
                    'timestamp' => Carbon::now(),
                ]);
            });

            return redirect()->route('barang.index')
                ->with('success', 'Barang berhasil ditambahkan!');

        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Error menyimpan barang: ' . $e->getMessage());

            if ($e->getCode() === '23505') {
                $attempt++;
                Log::info("Percobaan ke-$attempt: Konflik ID, transaksi di-rollback, mencoba lagi...");
                usleep(200000); 
                continue;
            }
            
            throw $e;
        }
    }

    return redirect()->back()->with('error', 'Gagal menambahkan barang karena konflik data berulang. Silakan coba lagi.');
}

    public function edit($id)
    {
        $barang      = Barang::orderBy('id_barang', 'asc')->get();
        $barang_edit = Barang::where('id_barang', $id)->firstOrFail(); 
        
        return view('barang.index', compact('barang', 'barang_edit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'  => 'required|string|max:50',
            'harga' => 'required|integer|min:0',
        ]);
        
        Barang::where('id_barang', $id)->update([
            'nama'  => $request->nama,
            'harga' => $request->harga,
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Hapus 
        Barang::where('id_barang', $id)->delete();
        
        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus!');
    }
    public function cetakTag(Request $request)
    {
        $x   = $request->input('x');
        $y   = $request->input('y');
        $ids = $request->input('id_barang');

        if (!$ids) {
            return redirect()->back()->with('error', 'Pilih minimal satu barang!');
        }

        $blank_spaces    = (($y - 1) * 5) + ($x - 1);
        $barang_terpilih = Barang::whereIn('id_barang', $ids)->get();

        $pdf = PDF::loadView('barang.pdf_tag', [
            'barang_terpilih' => $barang_terpilih,
            'blank_spaces'    => $blank_spaces,
        ]);
        
        return $pdf->setPaper('a4', 'portrait')->stream('Tag_Harga_ATK.pdf');
    }
}