@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-camera"></i>
        </span>
        <span class="text-muted" style="font-size:0.8rem;">Customer /</span> Tambah Customer 1 (Blob)
    </h3>
</div>

<div class="row justify-content-center">
<div class="col-md-8">
<div class="card shadow-sm border-0" style="border-radius:15px;">
    <div class="card-header bg-white fw-bold" style="color:#7243a1;border-bottom:2px solid #b66dff;">
        <i class="mdi mdi-database me-2"></i>Form Tambah Customer - Simpan Foto sebagai BLOB
    </div>
    <div class="card-body p-4">

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('customer.store1') }}" method="POST" id="formBlob">
    @csrf
    <input type="hidden" name="foto_blob"         id="foto_blob">
    <input type="hidden" name="kodepos"           id="kodeposHidden">
    <input type="hidden" name="kodepos_kelurahan" id="kelurahanNamaHidden">

    <div class="mb-3">
        <label class="fw-bold purple-label">Nama</label>
        <input type="text" name="nama" class="form-control purple-input" placeholder="Nama" required>
    </div>
    <div class="mb-3">
        <label class="fw-bold purple-label">Alamat</label>
        <input type="text" name="alamat" class="form-control purple-input" placeholder="Alamat">
    </div>
    <div class="mb-3">
        <label class="fw-bold purple-label">Provinsi</label>
        <select name="provinsi" id="provinsi" class="form-control purple-input">
            <option value="">Pilih Provinsi</option>
            @foreach($provinces as $p)
                <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="fw-bold purple-label">Kota</label>
        <select name="kota" id="kota" class="form-control purple-input" disabled>
            <option value="">Pilih Kota</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="fw-bold purple-label">Kecamatan</label>
        <select name="kecamatan" id="kecamatan" class="form-control purple-input" disabled>
            <option value="">Pilih Kecamatan</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="fw-bold purple-label">Kodepos - Kelurahan</label>
        <div class="row g-2">
            <div class="col-8">
                <select id="kelurahan" class="form-control purple-input" disabled>
                    <option value="">Pilih Kelurahan</option>
                </select>
            </div>
            <div class="col-4">
                <input type="text" id="kodeposDisplay"
                    class="form-control purple-input"
                    placeholder="Kodepos"
                    readonly>
            </div>
       
    </div>

    <div class="d-flex align-items-end gap-3 mt-4">
        <div id="boxFoto" style="width:150px;height:150px;border:2px solid #b66dff;
            border-radius:8px;display:flex;align-items:center;justify-content:center;
            background:#faf5ff;flex-shrink:0;overflow:hidden;">
            <img id="previewFoto" src="" alt="Foto"
                style="width:100%;height:100%;object-fit:cover;border-radius:6px;display:none;">
            <span id="labelFoto" style="color:#b66dff;" class="small">Foto</span>
        </div>
        <div class="d-flex flex-column gap-2">
            <button type="button" class="btn btn-primary px-4"
                data-bs-toggle="modal" data-bs-target="#modalKamera">
                <i class="mdi mdi-camera me-1"></i> Ambil Foto
            </button>
            <button type="submit" class="btn btn-success px-4" id="btnSimpan" disabled>
                <i class="mdi mdi-content-save me-1"></i> Simpan Data
            </button>
        </div>
    </div>

    </form>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="modalKamera" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-lg modal-dialog-centered">
<div class="modal-content" style="border:2px solid #b66dff;border-radius:12px;">
    <div class="modal-header" style="background:linear-gradient(135deg,#7243a1,#b66dff);">
        <h5 class="modal-title text-white">
            <i class="mdi mdi-camera me-2"></i>Modal ambil Foto
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body" style="background:#faf5ff;">
        <div class="row">
            <div class="col-md-6">
                <div style="border:2px solid #b66dff;border-radius:8px;overflow:hidden;
                    background:#000;height:220px;display:flex;align-items:center;justify-content:center;">
                    <video id="video" autoplay playsinline
                        style="width:100%;height:220px;object-fit:cover;display:none;"></video>
                    <span id="videoPlaceholder" class="text-white small">Video</span>
                </div>
                <p class="text-center small mt-1" style="color:#7243a1;">Video</p>
            </div>
            <div class="col-md-6">
                <div style="border:2px solid #b66dff;border-radius:8px;overflow:hidden;
                    background:#efe3f5;height:220px;display:flex;align-items:center;justify-content:center;">
                    <canvas id="canvas"
                        style="width:100%;height:100%;object-fit:cover;display:none;"></canvas>
                    <span id="snapPlaceholder" style="color:#b66dff;" class="small">Snapshot</span>
                </div>
                <p class="text-center small mt-1" style="color:#7243a1;">Snapshot</p>
            </div>
        </div>
    </div>
    <div class="modal-footer" style="background:#faf5ff;border-top:1px solid #b66dff;">
        <div class="d-flex justify-content-between w-100">
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" id="btnPilihKamera">
                    <i class="mdi mdi-camera-switch me-1"></i> Pilihan Kamera
                </button>
                <button type="button" class="btn btn-primary" id="btnAmbilFoto">
                    <i class="mdi mdi-camera-iris me-1"></i> Ambil Foto
                </button>
            </div>
            <button type="button" class="btn btn-success" id="btnSimpanFoto" disabled>
                <i class="mdi mdi-check me-1"></i> Simpan Foto
            </button>
        </div>
    </div>
</div>
</div>
</div>

<style>
.purple-label { color: #7243a1; }
.purple-input { border: 1.5px solid #b66dff !important; border-radius: 6px; }
.purple-input:focus { border-color: #7243a1 !important; box-shadow: 0 0 0 0.15rem rgba(182,109,255,0.25) !important; }
.purple-input:disabled { background-color: #f5f0ff; opacity: 0.7; }
.purple-input[readonly] { background-color: #f5f0ff; color: #7243a1; }
</style>

@endsection

@push('script')
<script>
document.getElementById('provinsi').addEventListener('change', function () {
    const id = this.value;
    resetSelect('kota',      'Pilih Kota');
    resetSelect('kecamatan', 'Pilih Kecamatan');
    resetSelect('kelurahan', 'Pilih Kelurahan');
    clearKodepos();
    if (!id) return;
    fetch('/wilayah/kota/' + id)
        .then(res => res.json())
        .then(data => fillSelect('kota', data, 'Pilih Kota'));
});

document.getElementById('kota').addEventListener('change', function () {
    const id = this.value;
    resetSelect('kecamatan', 'Pilih Kecamatan');
    resetSelect('kelurahan', 'Pilih Kelurahan');
    clearKodepos();
    if (!id) return;
    fetch('/wilayah/kecamatan/' + id)
        .then(res => res.json())
        .then(data => fillSelect('kecamatan', data, 'Pilih Kecamatan'));
});

document.getElementById('kecamatan').addEventListener('change', function () {
    const id = this.value;
    resetSelect('kelurahan', 'Pilih Kelurahan');
    clearKodepos();
    if (!id) return;
    fetch('/wilayah/kelurahan/' + id)
        .then(res => res.json())
        .then(data => fillSelect('kelurahan', data, 'Pilih Kelurahan'));
});

document.getElementById('kelurahan').addEventListener('change', function () {
    const namaKelurahan  = this.options[this.selectedIndex].text;
    const kodeposDisplay = document.getElementById('kodeposDisplay');
    const kodeposHidden  = document.getElementById('kodeposHidden');

    document.getElementById('kelurahanNamaHidden').value = namaKelurahan;

    if (!namaKelurahan || namaKelurahan === 'Pilih Kelurahan') {
        clearKodepos();
        return;
    }

    kodeposDisplay.value       = 'Mencari...';
    kodeposDisplay.placeholder = 'Mencari...';

    const namaKota = document.getElementById('kota')
        .options[document.getElementById('kota').selectedIndex].text.toLowerCase();

    fetch('https://kodepos.vercel.app/search/?q=' + encodeURIComponent(namaKelurahan))
        .then(res => res.json())
        .then(data => {
            if (data.statusCode === 200 && data.data.length > 0) {
                const cocok = data.data.find(item =>
                    item.regency.toLowerCase().includes(namaKota) ||
                    namaKota.includes(item.regency.toLowerCase())
                );
                const kodepos        = cocok ? cocok.code : data.data[0].code;
                kodeposDisplay.value = kodepos;
                kodeposHidden.value  = kodepos;
            } else {
                kodeposDisplay.value       = '';
                kodeposDisplay.placeholder = 'Tidak ditemukan';
                kodeposHidden.value        = '';
            }
        })
        .catch(() => {
            kodeposDisplay.value       = '';
            kodeposDisplay.placeholder = 'Gagal fetch';
            kodeposHidden.value        = '';
        });
});

function fillSelect(id, data, placeholder) {
    const select     = document.getElementById(id);
    let html         = `<option value="">${placeholder}</option>`;
    data.forEach(item => { html += `<option value="${item.id}">${item.name}</option>`; });
    select.innerHTML = html;
    select.disabled  = false;
}

function resetSelect(id, placeholder) {
    const select     = document.getElementById(id);
    select.innerHTML = `<option value="">${placeholder}</option>`;
    select.disabled  = true;
}

function clearKodepos() {
    document.getElementById('kodeposDisplay').value       = '';
    document.getElementById('kodeposDisplay').placeholder = 'Kodepos';
    document.getElementById('kodeposHidden').value        = '';
    document.getElementById('kelurahanNamaHidden').value  = '';
}

let stream       = null;
let devices      = [];
let deviceIndex  = 0;
let snapshotData = null;

const modalEl     = document.getElementById('modalKamera');
const modalKamera = new bootstrap.Modal(modalEl);

const video            = document.getElementById('video');
const canvas           = document.getElementById('canvas');
const videoPlaceholder = document.getElementById('videoPlaceholder');
const snapPlaceholder  = document.getElementById('snapPlaceholder');
const btnAmbilFoto     = document.getElementById('btnAmbilFoto');
const btnSimpanFoto    = document.getElementById('btnSimpanFoto');
const btnPilihKamera   = document.getElementById('btnPilihKamera');
const btnSimpan        = document.getElementById('btnSimpan');

modalEl.addEventListener('shown.bs.modal', () => {
    snapshotData                  = null;
    btnSimpanFoto.disabled        = true;
    canvas.style.display          = 'none';
    snapPlaceholder.style.display = 'block';
    startCamera();
});

modalEl.addEventListener('hidden.bs.modal', () => stopCamera());

async function startCamera(deviceId = null) {
    stopCamera();
    try {
        const constraints = {
            video: deviceId ? { deviceId: { exact: deviceId } } : { facingMode: 'user' }
        };
        stream                         = await navigator.mediaDevices.getUserMedia(constraints);
        video.srcObject                = stream;
        video.style.display            = 'block';
        videoPlaceholder.style.display = 'none';
        if (devices.length === 0) {
            const allDevices = await navigator.mediaDevices.enumerateDevices();
            devices          = allDevices.filter(d => d.kind === 'videoinput');
        }
    } catch (err) {
        alert('Tidak dapat mengakses kamera: ' + err.message);
    }
}

function stopCamera() {
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    video.style.display            = 'none';
    videoPlaceholder.style.display = 'block';
}

btnPilihKamera.addEventListener('click', function () {
    if (devices.length <= 1) { alert('Hanya ada 1 kamera.'); return; }
    deviceIndex = (deviceIndex + 1) % devices.length;
    startCamera(devices[deviceIndex].deviceId);
});

btnAmbilFoto.addEventListener('click', function () {
    if (!stream) { alert('Kamera belum aktif!'); return; }
    canvas.width  = video.videoWidth  || 640;
    canvas.height = video.videoHeight || 480;
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    canvas.style.display          = 'block';
    snapPlaceholder.style.display = 'none';
    snapshotData                  = canvas.toDataURL('image/png');
    btnSimpanFoto.disabled        = false;
});

btnSimpanFoto.addEventListener('click', function () {
    if (!snapshotData) { alert('Belum ada snapshot! Klik "Ambil Foto" dulu.'); return; }
    document.getElementById('foto_blob').value         = snapshotData;
    const prev                                         = document.getElementById('previewFoto');
    prev.src                                           = snapshotData;
    prev.style.display                                 = 'block';
    document.getElementById('labelFoto').style.display = 'none';
    btnSimpan.disabled                                 = false;
    modalKamera.hide();
});

document.getElementById('formBlob').addEventListener('submit', function (e) {
    if (!document.getElementById('foto_blob').value.trim()) {
        e.preventDefault();
        alert('Foto belum diambil! Silakan ambil foto terlebih dahulu.');
    }
});
</script>
@endpush