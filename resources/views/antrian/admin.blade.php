@extends('layouts.app')

@push('style')
<style>
    .nomor-badge { font-size: 1.4rem; font-weight: bold; }
    .list-terlambat { background: #fff3cd; }
    .estimasi-badge { font-size: 0.75rem; color: #6c757d; }

    #btn-panggil {
        transition: all 0.2s ease;
    }
    #btn-panggil:active {
        transform: scale(0.97);
    }
    #btn-panggil.loading {
        opacity: 0.75;
        pointer-events: none;
    }
    #btn-panggil.success {
        background-color: #0d6efd !important;
        border-color: #0d6efd !important;
    }
    #btn-panggil.error {
        background-color: #dc3545 !important;
        border-color: #dc3545 !important;
    }
</style>
@endpush

@section('content')
<div class="row mt-3">

    <div class="col-md-4">
        <div class="card border-success mb-3">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="mdi mdi-bullhorn"></i> Panggil Berikutnya</h5>
            </div>
            <div class="card-body text-center">

                <div id="sedang-dipanggil" class="mb-3">
                    <p class="text-muted">Belum ada yang dipanggil</p>
                </div>

                <div class="mb-2 text-start">
                    <label class="form-label fw-bold mb-1">Loket</label>
                    <select id="pilih-loket" class="form-select form-select-sm">
                        <option value="Loket 1">Loket 1</option>
                        <option value="Loket 2">Loket 2</option>
                        <option value="Loket 3">Loket 3</option>
                    </select>
                </div>

                <div class="mb-3 text-start">
                    <label class="form-label fw-bold mb-1">Ruangan</label>
                    <select id="pilih-ruangan" class="form-select form-select-sm">
                        <option value="Ruang 1">Ruang 1</option>
                        <option value="Ruang 2">Ruang 2</option>
                        <option value="Ruang 3">Ruang 3</option>
                        <option value="Ruang 4">Ruang 4</option>
                    </select>
                </div>

                <button id="btn-panggil" class="btn btn-success btn-lg w-100">
                    <i class="mdi mdi-arrow-right-circle"></i> Panggil Nomor Berikutnya
                </button>
            </div>
        </div>
    </div>

    <div class="col-md-8">

        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="mdi mdi-account-clock"></i> Antrian Menunggu
                    <span id="count-menunggu" class="badge bg-white text-primary ms-2">0</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Estimasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="list-menunggu">
                        <tr><td colspan="4" class="text-center text-muted">Belum ada antrian</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="mdi mdi-account-alert"></i> Antrian Terlambat
                    <span id="count-terlambat" class="badge bg-dark text-white ms-2">0</span>
                </h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="list-terlambat">
                        <tr><td colspan="3" class="text-center text-muted">Tidak ada yang terlambat</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@push('script')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// SSE
const source = new EventSource('{{ route("antrian.stream") }}');
source.addEventListener('queue-update', function(e) {
    const data = JSON.parse(e.data);
    renderList(data.list);
    renderDipanggil(data.sekarang);
});

function renderList(list) {
    const menunggu  = list.filter(i => i.status === 'menunggu');
    const terlambat = list.filter(i => i.status === 'terlambat');

    document.getElementById('count-menunggu').textContent  = menunggu.length;
    document.getElementById('count-terlambat').textContent = terlambat.length;

    const tbodyMenunggu = document.getElementById('list-menunggu');
    if (menunggu.length === 0) {
        tbodyMenunggu.innerHTML = '<tr><td colspan="4" class="text-center text-muted">Belum ada antrian</td></tr>';
    } else {
        tbodyMenunggu.innerHTML = menunggu.map((i, idx) => {
            const estimasi = (idx + 1) * 5;
            return `
                <tr>
                    <td><span class="nomor-badge text-primary">${String(i.nomor).padStart(3,'0')}</span></td>
                    <td>${i.nama}</td>
                    <td><span class="estimasi-badge"><i class="mdi mdi-clock-outline"></i> ~${estimasi} menit</span></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="tandaiTerlambat(${i.id})">
                            <i class="mdi mdi-account-remove"></i> Terlambat
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    const tbodyTerlambat = document.getElementById('list-terlambat');
    if (terlambat.length === 0) {
        tbodyTerlambat.innerHTML = '<tr><td colspan="3" class="text-center text-muted">Tidak ada yang terlambat</td></tr>';
    } else {
        tbodyTerlambat.innerHTML = terlambat.map(i => `
            <tr class="list-terlambat">
                <td><span class="nomor-badge text-warning">${String(i.nomor).padStart(3,'0')}</span></td>
                <td>${i.nama}</td>
                <td>
                    <button class="btn btn-sm btn-success" ondblclick="panggilTerlambat(${i.id})"
                            title="Double-click untuk panggil">
                        <i class="mdi mdi-phone"></i> Panggil (2x klik)
                    </button>
                </td>
            </tr>
        `).join('');
    }
}

function renderDipanggil(sekarang) {
    const el = document.getElementById('sedang-dipanggil');
    if (!sekarang) {
        el.innerHTML = '<p class="text-muted">Belum ada yang dipanggil</p>';
    } else {
        el.innerHTML = `
            <p class="text-muted mb-1">Sedang Dipanggil</p>
            <h2 class="text-success fw-bold">${String(sekarang.nomor).padStart(3,'0')}</h2>
            <h5>${sekarang.nama}</h5>
            <div class="mt-2 d-flex gap-2 justify-content-center flex-wrap">
                <span class="badge bg-info text-dark">
                    <i class="mdi mdi-door"></i> ${sekarang.ruangan ?? '-'}
                </span>
                <span class="badge bg-secondary">
                    <i class="mdi mdi-counter"></i> ${sekarang.loket ?? '-'}
                </span>
            </div>
        `;
    }
}

document.getElementById('btn-panggil').addEventListener('click', function() {
    const btn     = this;
    const ruangan = document.getElementById('pilih-ruangan').value;
    const loket   = document.getElementById('pilih-loket').value;

    btn.classList.add('loading');
    btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memanggil...';

    fetch('{{ route("antrian.panggil") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ ruangan, loket })
    })
    .then(res => res.json())
    .then(() => {

        btn.classList.remove('loading');
        btn.classList.add('success');
        btn.innerHTML = '<i class="mdi mdi-check-circle"></i> Berhasil Dipanggil!';

        setTimeout(() => {
            btn.classList.remove('success');
            btn.innerHTML = '<i class="mdi mdi-arrow-right-circle"></i> Panggil Nomor Berikutnya';
        }, 2000);
    })
    .catch(() => {
       
        btn.classList.remove('loading');
        btn.classList.add('error');
        btn.innerHTML = '<i class="mdi mdi-alert-circle"></i> Gagal, Coba Lagi';

        setTimeout(() => {
            btn.classList.remove('error');
            btn.innerHTML = '<i class="mdi mdi-arrow-right-circle"></i> Panggil Nomor Berikutnya';
        }, 2000);
    });
});

function tandaiTerlambat(id) {
    fetch('{{ route("antrian.terlambat") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ id })
    });
}

function panggilTerlambat(id) {
    const ruangan = document.getElementById('pilih-ruangan').value;
    const loket   = document.getElementById('pilih-loket').value;
    fetch('{{ route("antrian.panggil.terlambat") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ id, ruangan, loket })
    });
}
</script>
@endpush