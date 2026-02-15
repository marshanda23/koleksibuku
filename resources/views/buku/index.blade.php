@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-book-open-variant"></i>
        </span> 
        <span class="text-muted" style="font-size: 0.8rem; font-weight: normal;">Manajemen Data /</span> Buku
    </h3>
</div>

<div class="row">
    
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body">
                <h4 class="card-title text-primary">
                    <i class="mdi {{ isset($buku_edit) ? 'mdi-border-color' : 'mdi-plus-circle-outline' }} me-2"></i>
                    {{ isset($buku_edit) ? 'Edit Buku' : 'Tambah Buku' }}
                </h4>
                
                <form class="forms-sample mt-4" action="{{ isset($buku_edit) ? route('buku.update', $buku_edit->id) : route('buku.store') }}" method="POST">
                    @csrf
                    @if(isset($buku_edit)) @method('PUT') @endif

                    <div class="form-group">
                        <label class="font-weight-bold">Kategori</label>
                        <select name="kategori_id" class="form-control border-primary" style="border-radius: 8px;" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id }}" {{ (isset($buku_edit) && $buku_edit->kategori_id == $k->id) ? 'selected' : '' }}>
                                    {{ $k->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Kode Buku</label>
                        <input type="text" name="kode" class="form-control border-primary @error('kode') is-invalid @enderror" value="{{ isset($buku_edit) ? $buku_edit->kode : old('kode') }}" placeholder="Contoh: NV-01" style="border-radius: 8px;" required>
                        @error('kode')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Judul Buku</label>
                        <input type="text" name="judul" class="form-control border-primary" value="{{ isset($buku_edit) ? $buku_edit->judul : old('judul') }}" placeholder="Judul Buku" style="border-radius: 8px;" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Pengarang</label>
                        <input type="text" name="pengarang" class="form-control border-primary" value="{{ isset($buku_edit) ? $buku_edit->pengarang : old('pengarang') }}" placeholder="Nama Pengarang" style="border-radius: 8px;" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-gradient-primary btn-icon-text shadow-sm" style="border-radius: 8px;">
                            <i class="mdi mdi-file-check btn-icon-prepend"></i> 
                            {{ isset($buku_edit) ? 'Update' : 'Simpan' }}
                        </button>
                        @if(isset($buku_edit))
                            <a href="{{ route('buku.index') }}" class="btn btn-light shadow-sm" style="border-radius: 8px;">Batal</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="col-md-8 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0 text-primary"><i class="mdi mdi-table me-2"></i>Koleksi Buku</h4>
                    <span class="badge badge-gradient-info">{{ count($buku) }} Buku</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center" style="table-layout: fixed; width: 100%;">
                        <thead>
                            <tr style="border-bottom: 2px solid #acacac;">
                                <th class="py-3 font-weight-bold" style="width: 50px; color: #7243a1 !important;"> NO </th>
                                <th class="py-3 font-weight-bold" style="width: 100px; color: #7243a1 !important;"> KATEGORI </th>
                                <th class="py-3 font-weight-bold" style="width: 80px; color: #7243a1 !important;"> KODE </th>
                                <th class="py-3 font-weight-bold" style="color: #7243a1 !important;"> JUDUL </th>
                                <th class="py-3 font-weight-bold" style="width: 120px; color: #7243a1 !important;"> PENGARANG </th>
                                <th class="py-3 font-weight-bold" style="width: 100px; color: #7243a1 !important;"> AKSI </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($buku as $key => $item)
                            <tr style="border-bottom: 1px solid #f2edf3;">
                                <td class="py-3"><div class="badge badge-opacity-primary font-weight-bold">{{ $key + 1 }}</div></td>
                                <td class="py-3 text-wrap"><label class="badge badge-gradient-primary">{{ $item->kategori->nama_kategori }}</label></td>
                                <td class="py-3 text-muted small text-break">{{ $item->kode }}</td>
                                <td class="py-3 font-weight-bold text-dark text-wrap" style="line-height: 1.4; word-break: break-word;">
                                    {{ $item->judul }}
                                </td>
                                <td class="py-3 text-wrap" style="line-height: 1.4; word-break: break-word;">
                                    {{ $item->pengarang }}
                                </td>
                                <td class="py-3">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('buku.edit', $item->id) }}" class="btn btn-inverse-info btn-custom-icon me-2">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('buku.destroy', $item->id) }}" method="POST" class="delete-form m-0">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-inverse-danger btn-custom-icon btn-delete-trigger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">Belum ada koleksi buku.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false, iconColor: '#b66dff' });
    @endif

    {{-- Alert Error jika Validasi Gagal (Kode Kembar) --}}
    @if($errors->any())
        Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ $errors->first() }}", confirmButtonColor: '#fe7c96' });
    @endif

    document.querySelectorAll('.btn-delete-trigger').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            Swal.fire({
                title: 'Hapus buku ini?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fe7c96',
                cancelButtonColor: '#bdc3c7',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => { if (result.isConfirmed) form.submit(); });
        });
    });
</script>

<style>
    .btn-custom-icon { width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: none; }
    .badge-opacity-primary { background: #efe3f5; color: #b66dff; padding: 5px 10px; border-radius: 5px; font-weight: bold; }
    .table thead th { border: none !important; text-transform: uppercase; }
    .text-wrap { white-space: normal !important; }
    .card { transition: 0.3s; }
    .card:hover { transform: translateY(-5px); }
</style>
@endsection