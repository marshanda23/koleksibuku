@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nodejs"></i>
        </span>
        <span class="text-muted" style="font-size:0.8rem;">Manajemen Data /</span>
        DataTables
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
                            {{-- Placeholder dikosongkan agar tidak ada tulisan 'required' --}}
                            <input type="text" id="namaBarang" class="form-control custom-input" placeholder="" required>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-center">
                        <label class="col-sm-2 col-form-label fw-bold text-dark">Harga barang</label>
                        <div class="col-sm-10">
                            <input type="number" id="hargaBarang" class="form-control custom-input" placeholder="" required>
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

                {{-- Table --}}
                <div class="table-responsive">
                    <table id="tableBarang" class="table table-custom w-100">
                        <thead>
                            <tr>
                                <th style="width: 15%;">ID BARANG</th>
                                <th style="width: 55%;">NAMA BARANG</th>
                                <th style="width: 30%;">HARGA</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- EDIT --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:15px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-primary">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    
    .dataTables_length select {
        padding: 0.3rem 1.8rem 0.3rem 0.75rem !important;
        border: 1px solid #b66dff !important;
        border-radius: 4px;
    }

    .dataTables_filter input {
        border: 1px solid #b66dff !important;
        border-radius: 4px;
        padding: 5px 10px;
        outline: none;
    }

    .custom-input {
        border: 1px solid #b66dff !important;
        border-radius: 4px;
        padding: 0.7rem 1rem;
    }
    .custom-input:focus {
        border-color: #b66dff !important;
        box-shadow: 0 0 0 0.2rem rgba(182, 109, 255, 0.1);
    }

    .table-custom thead th {
        color: #b66dff !important;
        font-weight: 700;
        text-transform: uppercase;
        border-bottom: 2px solid #b66dff !important;
        padding: 18px 10px !important;
        text-align: center !important;
    }
    .table-custom tbody td {
        color: #000 !important;
        font-weight: 500;
        padding: 15px 10px !important;
        border-bottom: 1px solid #f2edf3 !important;
        vertical-align: middle !important;
        text-align: center; 
    }

    .badge-id {
        color: #b66dff;
        border: 1px solid #b66dff;
        padding: 3px 10px;
        font-size: 0.75rem;
        border-radius: 4px;
        font-weight: bold;
    }

    .btn-gradient-primary {
        background: linear-gradient(to right, #da8cff, #9a55ff);
        border: none;
        color: white;
    }
    .btn-gradient-primary:hover {
        opacity: 0.9;
        color: white;
    }
</style>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    let dataBarang = JSON.parse(localStorage.getItem("dataBarang")) || [];
    let selectedID = null;
    let table;

   $(document).ready(function(){
        if ($.fn.DataTable.isDataTable('#tableBarang')) {
            table = $('#tableBarang').DataTable(); 
        } else {

            table = $('#tableBarang').DataTable({
                pageLength: 5,
                autoWidth: false,
                language: {
                    lengthMenu: "Tampilkan _MENU_ data",
                    search: "Cari:",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: { previous: "Sebelumnya", next: "Berikutnya" }
                }
            });
        }
        renderTable();
    });
    function renderTable(){
    
        table.clear();
        
        dataBarang.forEach(item => {
            let row = table.row.add([
                `<span class="badge-id">${item.id}</span>`,
                `<strong>${item.nama}</strong>`,
                `Rp ${parseInt(item.harga).toLocaleString('id-ID')}`
            ]).node();
            
            $(row).attr('data-id', item.id);
            $(row).css('cursor', 'pointer');
        });
        
        table.draw();
    }

    $('#btnSimpan').click(function(){
        const form = document.getElementById('formTambah');
        if(!form.checkValidity()){
            form.reportValidity();
            return;
        }

        $('#labelSimpan').addClass('d-none');
        $('#spinnerSimpan').removeClass('d-none');
        $(this).prop('disabled', true);

        setTimeout(() => {
            const nama = $('#namaBarang').val();
            const harga = $('#hargaBarang').val();
            const id = "JS-" + (Math.floor(Math.random() * 9000) + 1000); 

            dataBarang.push({ id: id, nama: nama, harga: harga });
            localStorage.setItem("dataBarang", JSON.stringify(dataBarang));

            renderTable();
            $('#formTambah')[0].reset();

            $('#labelSimpan').removeClass('d-none');
            $('#spinnerSimpan').addClass('d-none');
            $('#btnSimpan').prop('disabled', false);

            Swal.fire({ icon: 'success', title: 'Berhasil!', text: 'Barang telah ditambahkan.', timer: 1500, showConfirmButton: false });
        }, 800);
    });

    $('#tableBarang tbody').on('click','tr',function(){
        const id = $(this).attr('data-id');
        selectedID = id;
        const data = dataBarang.find(item => item.id == id);
        if(data){
            $('#editID').val(data.id);
            $('#editNama').val(data.nama);
            $('#editHarga').val(data.harga);
            $('#modalEdit').modal('show');
        }
    });

    $('#btnUpdate').click(function(){
        $('#labelUpdate').addClass('d-none');
        $('#spinnerUpdate').removeClass('d-none');

        setTimeout(() => {
            const index = dataBarang.findIndex(item => item.id == selectedID);
            dataBarang[index].nama = $('#editNama').val();
            dataBarang[index].harga = $('#editHarga').val();
            localStorage.setItem("dataBarang", JSON.stringify(dataBarang));
            
            renderTable();
            $('#modalEdit').modal('hide');
            $('#labelUpdate').removeClass('d-none');
            $('#spinnerUpdate').addClass('d-none');

            Swal.fire({ icon: 'success', title: 'Diperbarui!', timer: 1000, showConfirmButton: false });
        }, 500);
    });

    $('#btnDelete').click(function(){
        Swal.fire({
            title: 'Hapus barang ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#b66dff',
            confirmButtonText: 'Ya, Hapus!'
        }).then((result) => {
            if(result.isConfirmed){
                dataBarang = dataBarang.filter(item => item.id != selectedID);
                localStorage.setItem("dataBarang", JSON.stringify(dataBarang));
                renderTable();
                $('#modalEdit').modal('hide');
                Swal.fire('Terhapus!', 'Data telah dibuang.', 'success');
            }
        });
    });
</script>
@endpush