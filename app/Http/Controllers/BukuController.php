<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index()
    {
        $kategori = Kategori::all();
        $buku = Buku::with('kategori')->orderBy('id', 'asc')->get();
        return view('buku.index', compact('buku', 'kategori'))->with('buku_edit', null);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required',
            'kode'        => 'required|unique:bukus,kode', // VALIDASI KODE UNIK
            'judul'       => 'required',
            'pengarang'   => 'required',
        ], [
            'kode.unique' => 'Kode buku sudah digunakan! Gunakan kode lain.',
        ]);

        Buku::create($request->all());
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $buku_edit = Buku::findOrFail($id);
        $kategori = Kategori::all();
        $buku = Buku::with('kategori')->orderBy('id', 'asc')->get();
        
        return view('buku.index', compact('buku', 'kategori', 'buku_edit'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'required',
            'kode'        => 'required|unique:bukus,kode,' . $id, // ABAIKAN KODE SENDIRI SAAT EDIT
            'judul'       => 'required',
            'pengarang'   => 'required',
        ], [
            'kode.unique' => 'Kode buku sudah digunakan oleh buku lain!',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return redirect()->route('buku.index')->with('success', 'Buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);
        $buku->delete();

        return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus!');
    }
}