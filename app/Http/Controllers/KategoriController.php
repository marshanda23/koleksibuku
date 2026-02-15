<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::orderBy('id', 'asc')->get();
        return view('kategori.index', compact('kategori'))->with('kategori_edit', null);
    }

    public function store(Request $request)
    {
        // Validasi: Nama harus unik di tabel kategoris
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori'
        ], [
            'nama_kategori.unique' => 'Nama kategori ini sudah ada! Gunakan nama lain.'
        ]);

        Kategori::create($request->all());

        return redirect()->route('kategori.index')->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori_edit = Kategori::findOrFail($id);
        $kategori = Kategori::orderBy('id', 'asc')->get();
        return view('kategori.index', compact('kategori', 'kategori_edit'));
    }

    public function update(Request $request, $id)
    {
        // Validasi: Nama unik, kecuali untuk kategori yang sedang diedit ini sendiri
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $id
        ], [
            'nama_kategori.unique' => 'Nama kategori sudah digunakan oleh data lain.'
        ]);

        $kategori = Kategori::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('kategori.index')->with('success', 'Perubahan kategori berhasil disimpan!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        
        // Proteksi jika kategori masih ada bukunya
        if ($kategori->buku()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori "' . $kategori->nama_kategori . '" tidak bisa dihapus karena masih digunakan oleh data buku!');
        }

        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus selamanya!');
    }
}