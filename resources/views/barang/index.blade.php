@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-tag-multiple"></i>
        </span> 
        <span class="text-muted" style="font-size: 0.8rem; font-weight: normal;">Manajemen Data /</span> Tag Harga ATK
    </h3>
</div>

<div class="row">

    {{--TABEL BARANG--}}
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0 text-primary">
                        <i class="mdi mdi-table me-2"></i>Daftar Barang ATK
                    </h4>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge badge-gradient-info">{{ count($barang) }} Item</span>
                        <button type="button" class="btn btn-gradient-primary btn-sm shadow-sm"
                                style="border-radius: 8px;" onclick="$('#modalTambah').modal('show')">
                            <i class="mdi mdi-plus me-1"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center" style="width: 100%;">
                        <thead>
                            <tr style="border-bottom: 2px solid #acacac;">
                                <th style="width: 50px; color: #7243a1 !important;">NO</th>
                                <th style="width: 120px; color: #7243a1 !important;">ID BARANG</th>
                                <th style="width: 250px; color: #7243a1 !important;">NAMA BARANG</th>
                                <th style="width: 140px; color: #7243a1 !important;">HARGA</th>
                                <th style="width: 160px; color: #7243a1 !important;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barang as $key => $item)
                            <tr style="border-bottom: 1px solid #f2edf3;">
                                <td><div class="badge badge-opacity-primary font-weight-bold">{{ $key + 1 }}</div></td>
                                <td class="text-muted small">{{ $item->id_barang }}</td>
                                <td class="font-weight-bold text-dark" style="text-align: left;">{{ $item->nama }}</td>
                                <td>
                                    <label class="badge badge-gradient-success">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </label>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- Edit --}}
                                        <button type="button"
                                                class="btn btn-inverse-info btn-label-icon btn-edit-trigger"
                                                data-id="{{ $item->id_barang }}"
                                                data-nama="{{ $item->nama }}"
                                                data-harga="{{ $item->harga }}">
                                            <i class="mdi mdi-pencil me-1"></i> Edit
                                        </button>
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('barang.destroy', $item->id_barang) }}" method="POST" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-inverse-danger btn-label-icon btn-delete-trigger">
                                                <i class="mdi mdi-delete me-1"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="mdi mdi-package-variant mdi-48px d-block mb-2"></i>
                                    Belum ada data barang.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{--CETAK--}}
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body">
                <h4 class="card-title text-primary">
                    <i class="mdi mdi-printer me-2"></i> Pengaturan Cetak Tag Harga
                </h4>
                <form action="{{ route('barang.cetak_tag') }}" method="POST" target="_blank" id="formCetak">
                    @csrf
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Mulai Kolom (X: 1-5)</label>
                                <input type="number" name="x" class="form-control border-primary" min="1" max="5" value="1" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Mulai Baris (Y: 1-8)</label>
                                <input type="number" name="y" class="form-control border-primary" min="1" max="8" value="1" required style="border-radius: 8px;">
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-center pt-2">
                            <button type="submit" class="btn btn-gradient-primary btn-icon-text shadow-sm" style="border-radius: 8px;">
                                <i class="mdi mdi-printer btn-icon-prepend"></i> Cetak label 
                            </button>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0 text-primary"><i class="mdi mdi-table me-2"></i>Pilih Barang ATK</h4>
                        <span class="badge badge-gradient-info">{{ count($barang) }} Item</span>
                    </div>

                    <div class="table-responsive">
                        <table id="tableBarang" class="table table-hover align-middle text-center" style="width: 100%;">
                            <thead>
                                <tr style="border-bottom: 2px solid #808080;">
                                    <th style="width: 50px; color: #7243a1 !important;"> PILIH </th>
                                    <th style="color: #7243a1 !important;"> ID BARANG </th>
                                    <th style="color: #7243a1 !important;"> NAMA BARANG </th>
                                    <th style="color: #7243a1 !important;"> HARGA </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barang as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="id_barang[]" value="{{ $item->id_barang }}" style="transform: scale(1.5);">
                                    </td>
                                    <td class="text-muted">{{ $item->id_barang }}</td>
                                    <td class="font-weight-bold text-dark">{{ $item->nama }}</td>
                                    <td><label class="badge badge-gradient-success">Rp {{ number_format($item->harga, 0, ',', '.') }}</label></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- tambah barang --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="labelTambah" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-primary" id="labelTambah">
                    <i class="mdi mdi-plus-circle-outline me-2"></i> Tambah Barang
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body pt-2">
                <form id="formTambah" action="{{ route('barang.store') }}" method="POST"> 
                    @csrf
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nama Barang</label>
                        <input type="text" name="nama"
                               class="form-control border-primary"
                               maxlength="50"
                               style="border-radius: 8px;" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Harga (Rp)</label>
                        <input type="number" name="harga"
                               class="form-control border-primary"
                               min="0"
                               style="border-radius: 8px;" required>
                    </div>
                    <div class="d-grid">
                        <button type="button" id="btnSimpanBarang"
                        class="btn btn-gradient-primary btn-icon-text shadow-sm"
                        style="border-radius: 8px;">
                    <span id="textSimpan">
                        <i class="mdi mdi-file-check btn-icon-prepend"></i> Simpan
                    </span>
                </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{--edit barang--}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="labelEdit" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-primary" id="labelEdit">
                    <i class="mdi mdi-border-color me-2"></i> Edit Barang
                </h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body pt-2">
                <form id="formEdit" action="" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nama Barang</label>
                        <input type="text" name="nama" id="editNama"
                               class="form-control border-primary"
                               maxlength="50"
                               style="border-radius: 8px;" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Harga (Rp)</label>
                        <input type="number" name="harga" id="editHarga"
                               class="form-control border-primary"
                               min="0"
                               style="border-radius: 8px;" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-gradient-primary btn-icon-text shadow-sm" style="border-radius: 8px;">
                            <i class="mdi mdi-file-check btn-icon-prepend"></i> Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // DataTable
        var table = $('#tableBarang').DataTable({
            "pageLength": 10,
            "language": { "search": "Cari:" }
        });

        $(document).on('click', '.btn-edit-trigger', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');

            $('#editNama').val(nama);
            $('#editHarga').val(harga);
            
            $('#formEdit').attr('action', '/barang/' + id);

            $('#modalEdit').modal('show');
        });

        $(document).on('click', '.btn-delete-trigger', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Hapus Barang?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fe7c96',
                cancelButtonColor: '#bdc3c7',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        $('#formCetak').on('submit', function(e) {
            if ($('input[name="id_barang[]"]:checked').length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Pilih Barang!',
                    text: 'Centang minimal satu barang.',
                    confirmButtonColor: '#fe7c96'
                });
            }
        });
    });

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

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ $errors->first() }}",
            confirmButtonColor: '#fe7c96'
        });
    @endif
</script>

<style>
    .border-primary { border: 1px solid #b66dff !important; }
    .table thead th { border: none !important; text-transform: uppercase; font-weight: bold; }
    .btn-custom-icon { width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: none; }
    .btn-label-icon { height: 32px; padding: 0 10px; display: inline-flex; align-items: center; border-radius: 8px; border: none; font-size: 0.82rem; font-weight: 600; }
    .badge-opacity-primary { background: #efe3f5; color: #b66dff; padding: 5px 10px; border-radius: 5px; font-weight: bold; }
    .card { transition: 0.3s; }
    .card:hover { transform: translateY(-5px); }
    .modal-content { box-shadow: 0 10px 40px rgba(114, 67, 161, 0.15); }
</style>
@endsection