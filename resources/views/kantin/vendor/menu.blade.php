@extends('kantin.vendor.layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-food"></i>
        </span>
        Kelola Menu
    </h3>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="border-radius:12px">
            <div class="card-body">

                <h4 class="text-primary mb-4">
                    <i class="mdi mdi-plus-circle-outline me-2"></i>
                    Tambah Menu
                </h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('kantin.vendor.menu.store') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label>Nama Menu</label>
                        <input type="text"
                               name="nama_menu"
                               class="form-control border-primary"
                               placeholder=""
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number"
                               name="harga"
                               class="form-control border-primary"
                               placeholder=""
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Gambar <small class="text-muted">(opsional)</small></label>
                        <input type="file"
                               name="gambar"
                               class="form-control border-primary"
                               accept="image/*">
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="mdi mdi-content-save me-1"></i> Simpan Menu
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0" style="border-radius:12px">
            <div class="card-body">

                <h4 class="text-primary mb-4">
                    <i class="mdi mdi-format-list-bulleted me-2"></i>
                    Daftar Menu
                </h4>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:50px">No</th>
                                <th class="text-center">Nama Menu</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Gambar</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $i => $m)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td class="text-center">{{ $m->nama_menu }}</td>
                                <td class="text-center">Rp {{ number_format($m->harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($m->path_gambar)
                                        <img src="{{ asset('storage/' . $m->path_gambar) }}"
                                             width="50" height="50"
                                             style="object-fit:cover;border-radius:6px">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        {{-- Tombol Edit --}}
                                        <button type="button"
                                                class="btn btn-success btn-sm"
                                                onclick="bukaEdit({{ $m->idmenu }}, '{{ addslashes($m->nama_menu) }}', {{ $m->harga }})">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('kantin.vendor.menu.destroy', $m->idmenu) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus menu ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-muted text-center">Belum ada menu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:12px">
            <div class="modal-header border-0">
                <h5 class="modal-title text-primary">
                    <i class="mdi mdi-pencil me-2"></i> Edit Menu
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nama Menu</label>
                        <input type="text" name="nama_menu" id="edit_nama"
                               class="form-control border-primary" required>
                    </div>

                    <div class="mb-3">
                        <label>Harga</label>
                        <input type="number" name="harga" id="edit_harga"
                               class="form-control border-primary" required>
                    </div>

                    <div class="mb-3">
                        <label>Gambar Baru <small class="text-muted">(opsional)</small></label>
                        <input type="file" name="gambar"
                               class="form-control border-primary" accept="image/*">
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-gradient-primary">
                        <i class="mdi mdi-content-save me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.border-primary { border: 1px solid #b66dff !important; }
.table thead th {
    border: none !important;
    font-weight: bold;
    color: #7b4bb7;
}
</style>

<script>
function bukaEdit(id, nama, harga) {
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_harga').value = harga;
    document.getElementById('formEdit').action = '/kantin/vendor/menu/' + id;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>

@endsection