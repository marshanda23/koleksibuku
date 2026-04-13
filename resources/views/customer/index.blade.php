@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account-group"></i>
        </span>
        <span class="text-muted" style="font-size:0.8rem;">Customer /</span> Data Customer
    </h3>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius:15px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0 text-primary">
                        <i class="mdi mdi-table me-2"></i>Data Customer
                    </h4>
                    <span class="badge badge-gradient-info">{{ count($customers) }} Customer</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center">
                        <thead>
                            <tr style="border-bottom: 2px solid #acacac;">
    <th style="color:#7243a1;">NO</th>
    <th style="color:#7243a1;">FOTO</th>
    <th style="color:#7243a1;">NAMA</th>
    <th style="color:#7243a1;">ALAMAT</th>
    <th style="color:#7243a1;">PROVINSI</th>
    <th style="color:#7243a1;">KOTA</th>
    <th style="color:#7243a1;">KECAMATAN</th>
    <th style="color:#7243a1;">KELURAHAN</th>
    <th style="color:#7243a1;">KODEPOS</th>
    <th style="color:#7243a1;">TIPE FOTO</th>
</tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $key => $item)
                            <tr style="border-bottom:1px solid #f2edf3; cursor: pointer;" 
                                class="customer-row"
                                data-id="{{ $item->id }}"
                                data-nama="{{ $item->nama }}">
                                
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if($item->foto_blob)
                                        <img src="{{ $item->foto_blob }}" style="width:55px;height:55px;object-fit:cover;border-radius:50%;border:2px solid #b66dff;">
                                    @elseif($item->foto_path)
                                        <img src="{{ Storage::url($item->foto_path) }}" style="width:55px;height:55px;object-fit:cover;border-radius:50%;border:2px solid #b66dff;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
<td class="font-weight-bold">{{ $item->nama }}</td>
<td>{{ $item->alamat ?? '-' }}</td>
<td>{{ $item->nama_provinsi ?? '-' }}</td>
<td>{{ $item->nama_kota ?? '-' }}</td>
<td>{{ $item->nama_kecamatan ?? '-' }}</td>
<td>{{ $item->kodepos_kelurahan ?? '-' }}</td>
<td>{{ $item->kodepos ?? '-' }}</td>
<td>
                                    @if($item->foto_blob)
                                        <span class="badge badge-gradient-warning">BLOB</span>
                                    @else
                                        <span class="badge badge-gradient-success">FILE</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center py-5 text-muted">Belum ada data customer.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalOpsi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title w-100 text-center text-primary font-weight-bold" id="modalNama">Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-2">
                <div class="d-grid gap-2">
                    <a href="#" id="btnEdit" class="btn btn-gradient-primary btn-icon-text p-3">
                        <i class="mdi mdi-pencil me-2"></i> Edit Data
                    </a>
                    <form id="formDelete" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100 p-3" onclick="return confirm('Hapus data ini?')">
                            <i class="mdi mdi-delete me-2"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.customer-row');
        const modalOpsi = new bootstrap.Modal(document.getElementById('modalOpsi'));
        const btnEdit = document.getElementById('btnEdit');
        const formDelete = document.getElementById('formDelete');
        const modalNamaHeader = document.getElementById('modalNama');

        rows.forEach(row => {
            row.addEventListener('click', function() {
                const id = this.dataset.id;
                const nama = this.dataset.nama;

                modalNamaHeader.innerText = nama;
                btnEdit.href = `/customer/${id}/edit`;
                formDelete.action = `/customer/${id}`;

                modalOpsi.show();
            });
        });

        @if(session('success'))
            Swal.fire({ 
                icon:'success', title:'Berhasil!', text:"{{ session('success') }}", 
                timer:2000, showConfirmButton:false, iconColor:'#b66dff' 
            });
        @endif
    });
</script>
@endpush