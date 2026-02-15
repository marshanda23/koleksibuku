@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-format-list-bulleted"></i>
        </span> 
        <span class="text-muted" style="font-size: 0.8rem; font-weight: normal;">Manajemen Data /</span> Kategori
    </h3>
</div>

<div class="row">
    
    <div class="col-md-4 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body">
                <h4 class="card-title text-primary">
                    <i class="mdi {{ isset($kategori_edit) ? 'mdi-border-color' : 'mdi-plus-circle-outline' }} me-2"></i>
                    {{ isset($kategori_edit) ? 'Edit Kategori' : 'Tambah Kategori' }}
                </h4>
                
                <form class="forms-sample mt-4" action="{{ isset($kategori_edit) ? route('kategori.update', $kategori_edit->id) : route('kategori.store') }}" method="POST">
                    @csrf
                    @if(isset($kategori_edit)) @method('PUT') @endif

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Kategori</label>
                        <input type="text" class="form-control border-primary @error('nama_kategori') is-invalid @enderror" 
                               name="nama_kategori" 
                               value="{{ isset($kategori_edit) ? $kategori_edit->nama_kategori : old('nama_kategori') }}" 
                               required style="border-radius: 8px;" placeholder="Contoh: Novel">
                        {{-- Pesan Error Validasi --}}
                        @error('nama_kategori')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-gradient-primary btn-icon-text shadow-sm" style="border-radius: 8px;">
                            <i class="mdi mdi-file-check btn-icon-prepend"></i> 
                            {{ isset($kategori_edit) ? 'Update' : 'Simpan' }}
                        </button>
                        @if(isset($kategori_edit))
                            <a href="{{ route('kategori.index') }}" class="btn btn-light shadow-sm" style="border-radius: 8px;">Batal</a>
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
                    <h4 class="card-title mb-0 text-primary"><i class="mdi mdi-table me-2"></i>Daftar Kategori</h4>
                    <span class="badge badge-gradient-info">{{ count($kategori) }} Total</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead>
                            <tr style="border-bottom: 2px solid #acacac;">
                                <th class="py-3 font-weight-bold" style="font-size: 0.85rem; color: #7243a1 !important;"> NO </th>
                                <th class="py-3 font-weight-bold" style="font-size: 0.85rem; color: #7243a1 !important;"> NAMA KATEGORI </th>
                                <th class="py-3 font-weight-bold" style="font-size: 0.85rem; color: #7243a1 !important;"> AKSI </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategori as $index => $item)
                            <tr style="border-bottom: 1px solid #f2edf3;">
                                <td class="py-3">
                                    <div class="badge badge-opacity-primary font-weight-bold">{{ $index + 1 }}</div>
                                </td>
                                <td class="font-weight-bold py-3" style="color:#333;">
                                    {{ $item->nama_kategori }}
                                </td>
                                <td class="py-3">
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-inverse-info btn-custom-icon me-2">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" class="delete-form m-0">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-inverse-danger btn-custom-icon btn-delete-trigger">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="py-5 text-muted">Belum ada data kategori.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 2000,
            showConfirmButton: false,
            iconColor: '#b66dff'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#b66dff'
        });
    @endif

    {{-- Notifikasi Error Validasi (Nama Kembar) --}}
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ $errors->first() }}",
            confirmButtonColor: '#fe7c96'
        });
    @endif

    document.querySelectorAll('.btn-delete-trigger').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data kategori ini akan dihapus permanen!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#fe7c96',
                cancelButtonColor: '#bdc3c7',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>

<style>
    .btn-custom-icon { width: 35px; height: 35px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: none; }
    .badge-opacity-primary { background: #efe3f5; color: #b66dff; padding: 5px 10px; border-radius: 5px; font-weight: bold; }
    .table thead th { border: none !important; }
    .card { transition: 0.3s; }
    .card:hover { transform: translateY(-5px); }
</style>
@endsection