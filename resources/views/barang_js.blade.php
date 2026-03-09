@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nodejs"></i>
        </span>
        <span class="text-muted" style="font-size:0.8rem;">Manajemen Data /</span>
        HTML Table
    </h3>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius:10px;">
            <div class="card-body p-4">

                {{-- FORM INPUT --}}
                <form id="formTambah" class="mb-5">
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-2 col-form-label fw-bold text-dark">Nama barang</label>
                        <div class="col-sm-10">
                            <input type="text" id="namaBarang" class="form-control custom-input" required>
                        </div>
                    </div>
                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-2 col-form-label fw-bold text-dark">Harga barang</label>
                        <div class="col-sm-10">
                            <input type="number" id="hargaBarang" class="form-control custom-input" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-end">
                            <button type="button" id="btnSimpan" class="btn btn-gradient-primary px-5 py-2">
                                <span id="labelSimpan">Submit</span>
                                <span id="spinnerSimpan" class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </div>
                    </div>
                </form>

                <hr class="mb-5" style="border-top: 2px solid #b66dff;">

                {{-- TABEL HTML --}}
                <div class="table-responsive">
                    <table class="table table-custom w-100">
                        <thead>
                            <tr>
                                <th style="width: 15%;">ID BARANG</th>
                                <th style="width: 55%;">NAMA BARANG</th>
                                <th style="width: 30%;">HARGA</th>
                            </tr>
                        </thead>
                        <tbody id="bodyBarangJS"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- EDIT --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:15px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-primary">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formEdit">
                    <div class="form-group mb-3">
                        <label class="text-muted small">ID Barang</label>
                        <input type="text" id="editID" class="form-control bg-light" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold">Nama Barang</label>
                        <input type="text" id="editNama" class="form-control custom-input" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="fw-bold">Harga</label>
                        <input type="number" id="editHarga" class="form-control custom-input" required>
                    </div>
                </form>
                <div class="d-flex gap-2">
                    <button class="btn btn-gradient-primary w-100 py-2 fw-bold" id="btnUpdate">
                        <span id="labelUpdate">Ubah</span>
                        <span id="spinnerUpdate" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                    <button class="btn btn-light w-50 py-2 border" id="btnDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .custom-input { border: 1px solid #b66dff !important; border-radius: 4px; padding: 0.7rem 1rem; }
    .table-custom thead th { color: #b66dff !important; text-align: center !important; border-bottom: 2px solid #b66dff !important; padding: 18px 10px !important; }
    .table-custom tbody td { text-align: center !important; padding: 15px 10px !important; border-bottom: 1px solid #f2edf3 !important; }
    .badge-id { color: #b66dff; border: 1px solid #b66dff; padding: 3px 10px; border-radius: 4px; font-weight: bold; }
    tbody tr:hover { cursor: pointer; background: #fcfaff; }
</style>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let dataBarang = [];
    let selectedIndex = null;

    function renderTable() {
        const body = $('#bodyBarangJS');
        body.empty();
        if (dataBarang.length === 0) {
            body.append('<tr><td colspan="3" class="py-4 text-muted">Belum ada data</td></tr>');
            return;
        }
        dataBarang.forEach((item, index) => {
            body.append(`
                <tr data-index="${index}">
                    <td><span class="badge-id">${item.id}</span></td>
                    <td><strong>${item.nama}</strong></td>
                    <td>Rp ${parseInt(item.harga).toLocaleString('id-ID')}</td>
                </tr>
            `);
        });
    }

    $('#btnSimpan').click(function(){
        const form = document.getElementById('formTambah');
        if(!form.checkValidity()){ form.reportValidity(); return; }

        $('#labelSimpan').addClass('d-none');
        $('#spinnerSimpan').removeClass('d-none');
        $(this).prop('disabled', true);

        setTimeout(() => {
            dataBarang.push({
                id: "JS-" + Math.floor(Math.random() * 9000 + 1000),
                nama: $('#namaBarang').val(),
                harga: $('#hargaBarang').val()
            });
            renderTable();
            $('#formTambah')[0].reset();
            $('#labelSimpan').removeClass('d-none');
            $('#spinnerSimpan').addClass('d-none');
            $('#btnSimpan').prop('disabled', false);
            
            Swal.fire({ icon: 'success', title: 'Berhasil!',
             text: 'Barang ditambahkan', timer: 1500, 
             showConfirmButton: false });
        }, 800);
    });

    // klik baris
    $(document).on('click', '#bodyBarangJS tr', function(){
        selectedIndex = $(this).data('index');
        const data = dataBarang[selectedIndex];
        $('#editID').val(data.id);
        $('#editNama').val(data.nama);
        $('#editHarga').val(data.harga);
        $('#modalEdit').modal('show');
    });

    $('#btnUpdate').click(function(){
        $('#labelUpdate').addClass('d-none');
        $('#spinnerUpdate').removeClass('d-none');
        $(this).prop('disabled', true);

        setTimeout(() => {
            dataBarang[selectedIndex].nama = $('#editNama').val();
            dataBarang[selectedIndex].harga = $('#editHarga').val();
            renderTable();
            $('#modalEdit').modal('hide');
            
            $('#labelUpdate').removeClass('d-none');
            $('#spinnerUpdate').addClass('d-none');
            $('#btnUpdate').prop('disabled', false);
            
            Swal.fire({ icon: 'success', title: 'Diperbarui!', timer: 1000, showConfirmButton: false });
        }, 800);
    });

    $('#btnDelete').click(function(){
        Swal.fire({ title: 'Hapus data?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#b66dff' }).then((result) => {
            if(result.isConfirmed){
                dataBarang.splice(selectedIndex, 1);
                renderTable();
                $('#modalEdit').modal('hide');
            }
        });
    });
</script>
@endpush