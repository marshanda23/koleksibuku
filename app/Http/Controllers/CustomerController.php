<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = DB::table('customers')
            ->leftJoin('reg_provinces', 'customers.provinsi', '=', 'reg_provinces.id')
            ->leftJoin('reg_regencies', 'customers.kota', '=', 'reg_regencies.id')
            ->leftJoin('reg_districts', 'customers.kecamatan', '=', 'reg_districts.id')
            ->select(
                'customers.*',
                'reg_provinces.name as nama_provinsi',
                'reg_regencies.name as nama_kota',
                'reg_districts.name as nama_kecamatan'
            )
            ->orderBy('customers.id', 'desc')
            ->get();

        return view('customer.index', compact('customers'));
    }

    public function tambah1()
    {
        $provinces = DB::table('reg_provinces')->orderBy('name')->get();
        return view('customer.tambah1', compact('provinces'));
    }

    public function store1(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:100',
            'alamat'            => 'nullable|string',
            'provinsi'          => 'nullable|string',
            'kota'              => 'nullable|string',
            'kecamatan'         => 'nullable|string',
            'kodepos_kelurahan' => 'nullable|string',
            'kodepos'           => 'nullable|string',
            'foto_blob'         => 'required|string',
        ]);

        Customer::create([
            'nama'              => $request->nama,
            'alamat'            => $request->alamat,
            'provinsi'          => $request->provinsi,
            'kota'              => $request->kota,
            'kecamatan'         => $request->kecamatan,
            'kodepos_kelurahan' => $request->kodepos_kelurahan,
            'kodepos'           => $request->kodepos,
            'foto_blob'         => $request->foto_blob,
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan (Blob)!');
    }

    public function tambah2()
    {
        $provinces = DB::table('reg_provinces')->orderBy('name')->get();
        return view('customer.tambah2', compact('provinces'));
    }

    public function store2(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:100',
            'alamat'            => 'nullable|string',
            'provinsi'          => 'nullable|string',
            'kota'              => 'nullable|string',
            'kecamatan'         => 'nullable|string',
            'kodepos_kelurahan' => 'nullable|string',
            'kodepos'           => 'nullable|string',  // ← ditambahkan
            'foto'              => 'required|string',
        ]);

        $base64    = $request->foto;
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $imageData = base64_decode($imageData);
        $filename  = 'customer_' . time() . '_' . uniqid() . '.png';
        $path      = 'customers/' . $filename;
        Storage::disk('public')->put($path, $imageData);

        Customer::create([
            'nama'              => $request->nama,
            'alamat'            => $request->alamat,
            'provinsi'          => $request->provinsi,
            'kota'              => $request->kota,
            'kecamatan'         => $request->kecamatan,
            'kodepos_kelurahan' => $request->kodepos_kelurahan,
            'kodepos'           => $request->kodepos,  // ← ditambahkan
            'foto_path'         => $path,
        ]);

        return redirect()->route('customer.index')
            ->with('success', 'Customer berhasil ditambahkan (File)!');
    }

    public function edit($id)
    {
        $customer  = Customer::findOrFail($id);
        $provinces = DB::table('reg_provinces')->orderBy('name')->get();

        $regencies = DB::table('reg_regencies')
            ->where('province_id', $customer->provinsi)
            ->orderBy('name')
            ->get();

        $districts = DB::table('reg_districts')
            ->where('regency_id', $customer->kota)
            ->orderBy('name')
            ->get();

        return view('customer.edit', compact('customer', 'provinces', 'regencies', 'districts'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'nama'              => 'required|string|max:100',
            'alamat'            => 'nullable|string',
            'provinsi'          => 'nullable|string',
            'kota'              => 'nullable|string',
            'kecamatan'         => 'nullable|string',
            'kodepos_kelurahan' => 'nullable|string',
            'kodepos'           => 'nullable|string',
            'foto_baru'         => 'nullable|string',
        ]);

        $data = [
            'nama'              => $request->nama,
            'alamat'            => $request->alamat,
            'provinsi'          => $request->provinsi,
            'kota'              => $request->kota,
            'kecamatan'         => $request->kecamatan,
            'kodepos_kelurahan' => $request->kodepos_kelurahan,
            'kodepos'           => $request->kodepos,
        ];

        if ($request->filled('foto_baru')) {
            $base64    = $request->foto_baru;
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
            $imageData = base64_decode($imageData);

            if ($customer->foto_blob) {
                $data['foto_blob'] = $request->foto_baru;
                $data['foto_path'] = null;
            } else {
                if ($customer->foto_path) {
                    Storage::disk('public')->delete($customer->foto_path);
                }
                $filename          = 'customer_' . time() . '_' . uniqid() . '.png';
                $path              = 'customers/' . $filename;
                Storage::disk('public')->put($path, $imageData);
                $data['foto_path'] = $path;
                $data['foto_blob'] = null;
            }
        }

        $customer->update($data);

        return redirect()->route('customer.index')
            ->with('success', 'Data customer berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        if ($customer->foto_path) {
            Storage::disk('public')->delete($customer->foto_path);
        }

        $customer->delete();

        return redirect()->route('customer.index')
            ->with('success', 'Data customer berhasil dihapus.');
    }
}