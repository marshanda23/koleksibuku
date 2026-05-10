@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-store"></i>
        </span>
        <span class="text-muted" style="font-size:0.8rem;">Sales /</span> Kunjungan Toko
    </h3>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-body p-4">

                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="badge badge-gradient-primary" style="font-size:0.7rem; padding:4px 10px;">① ADMIN</span>
                    <h5 class="mb-0 fw-bold text-primary">Input Titik Awal Toko</h5>
                </div>
                <p class="text-muted mb-4" style="font-size:0.85rem;">
                    <i class="mdi mdi-information-outline me-1"></i>
                    Buka <a href="https://maps.google.com" target="_blank">Google Maps</a>,
                    mencari lokasi toko
                </p>

                <form action="{{ route('kunjungan.simpanToko') }}" method="POST">
                    @csrf

<div class="row g-3 mb-3">
    <div class="col-12">
        <label class="form-label fw-bold" style="font-size:0.85rem;">Nama Toko</label>
        <input type="text" name="nama_toko" class="form-control border"
               style="border-color:#adb5bd !important;"required>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label class="form-label fw-bold" style="font-size:0.85rem;">Latitude</label>
        <input type="number" name="latitude" step="any" class="form-control border"
               style="border-color:#adb5bd !important;" required>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold" style="font-size:0.85rem;">Longitude</label>
        <input type="number" name="longitude" step="any" class="form-control border"
               style="border-color:#adb5bd !important;" required>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold" style="font-size:0.85rem;">Accuracy (m)</label>
        <input type="number" name="accuracy" step="any" value="10"
               class="form-control border"
               style="border-color:#adb5bd !important;" required>
    </div>
</div>

<div class="d-flex">
    <button type="submit" class="btn btn-gradient-primary px-4">
        <i class="mdi mdi-check me-1"></i> Simpan Toko
    </button>
</div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-body p-4">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <h5 class="mb-0 fw-bold text-primary">List Toko</h5>
                    </div>
                    <span class="badge badge-gradient-success px-3 py-2">
                        <i class="mdi mdi-store me-1"></i>{{ count($tokoList) }} Toko
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle text-center mb-0">
                        <thead>
                            <tr style="background:#f8f6ff;">
                                <th class="text-primary border-0" style="font-size:0.8rem;">NO</th>
                                <th class="text-primary border-0" style="font-size:0.8rem;">BARCODE</th>
                                <th class="text-primary border-0" style="font-size:0.8rem;">NAMA TOKO</th>
                                <th class="text-primary border-0" style="font-size:0.8rem;">LATITUDE</th>
                                <th class="text-primary border-0" style="font-size:0.8rem;">LONGITUDE</th>
                                <th class="text-primary border-0" style="font-size:0.8rem;">ACCURACY</th>
                                <th class="text-primary border-0" style="font-size:0.8rem;">CETAK</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tokoList as $key => $toko)
                            <tr>
                                <td style="font-size:0.85rem; color:#212529;">{{ $key + 1 }}</td>
                                <td><code style="color:#b66dff; font-size:0.8rem;">{{ $toko->barcode }}</code></td>
                                <td class="fw-bold" style="font-size:0.85rem; color:#212529;">{{ $toko->nama_toko }}</td>
                                <td style="font-size:0.85rem; color:#212529;">{{ $toko->latitude }}</td>
                                <td style="font-size:0.85rem; color:#212529;">{{ $toko->longitude }}</td>
                                <td><span class="badge badge-gradient-success">{{ $toko->accuracy }} m</span></td>
                                <td>
                                    <button class="btn btn-sm" style="border:1.5px solid #6c757d; color:#495057;"
                                        onclick="cetakBarcode('{{ $toko->barcode }}', '{{ $toko->nama_toko }}')">
                                        <i class="mdi mdi-printer me-1"></i>Cetak
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="mdi mdi-store-off mdi-48px d-block mb-2"></i>
                                    Belum ada data toko.
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

<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-body p-4">

                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="badge badge-gradient-primary" style="font-size:0.7rem; padding:4px 10px;">③ SALES</span>
                    <h5 class="mb-0 fw-bold text-primary">Titik Kunjungan</h5>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6 d-flex flex-column">
                        <label class="form-label fw-bold" style="font-size:0.85rem;">
                            <i class="mdi mdi-barcode-scan me-1"></i>Scan / Input Barcode Toko
                        </label>
                        <div class="input-group gap-2">
                        <input type="text" id="inputBarcode" class="form-control"
                        style="border-color: #adadad !important;"
                            placeholder="ketik barcode toko">
                        <button class="btn btn-gradient-primary rounded" onclick="bukaModalScan()" title="Scan via Kamera">
                            <i class="mdi mdi-camera"></i>
                        </button>
                        <button class="btn btn-gradient-primary rounded" onclick="cariToko()">
                            <i class="mdi mdi-magnify"></i> Cari
                        </button>
                    </div>
                    </div>
                    <div class="col-md-6 d-flex flex-column">
                        <label class="form-label fw-bold" style="font-size:0.85rem;">
                            <i class="mdi mdi-store me-1"></i>Data Toko (dari Database)
                        </label>
                        <div id="infoToko" class="p-3 rounded flex-grow-1"
                             style="background:#f8f6ff; border:1px solid #adadad; font-size:0.85rem; min-height:46px;">
                            <span class="text-muted">— Belum ada data toko, silakan scan barcode —</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6 d-flex flex-column">
                        <label class="form-label fw-bold" style="font-size:0.85rem;">
                            <i class="mdi mdi-crosshairs-gps me-1"></i>Lokasi Sales Saat Ini
                        </label>
                        <button id="btnAmbilLokasi" class="btn btn-gradient-primary w-100 py-2"
                                onclick="ambilLokasi()">
                            <i class="mdi mdi-map-marker me-1"></i> Ambil Lokasi GPS
                        </button>
                    </div>
                    <div class="col-md-6 d-flex flex-column">
                        <label class="form-label fw-bold" style="font-size:0.85rem;">
                            <i class="mdi mdi-map-marker-radius me-1"></i>Koordinat Sales
                        </label>
                        <div id="infoLokasi" class="p-3 rounded flex-grow-1"
                             style="background:#f8f6ff; border:1px solid #adadad; font-size:0.85rem; min-height:46px;">
                            <span class="text-muted">— Belum diambil —</span>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 d-flex justify-content-center">
                        <button id="btnSubmit" class="btn btn-gradient-success px-5 py-2"
                                onclick="submitKunjungan()" disabled>
                            <i class="mdi mdi-check-circle me-1"></i> Submit Kunjungan
                        </button>
                    </div>
                </div>

                <div class="col-12 mt-3" id="hasilKunjungan" style="display:none;">
                    <div id="alertHasil" class="alert mb-0 rounded-3"></div>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalBarcode" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:16px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title w-100 text-center text-primary fw-bold" id="modalNamaToko">Barcode Toko</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <canvas id="barcodeCanvas"></canvas>
                <p class="mt-2 text-muted" id="labelBarcode" style="font-size:0.75rem;"></p>
            </div>
            <div class="modal-footer border-0 pt-0 d-grid">
                <button class="btn btn-gradient-primary" onclick="window.print()">
                    <i class="mdi mdi-printer me-1"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalScanKamera" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0" style="border-radius:16px; overflow:hidden;">
            <div class="modal-header bg-gradient-primary text-white border-0 py-3">
                <h5 class="modal-title w-100 text-center mb-0">
                    <i class="mdi mdi-camera me-2"></i>Kamera Scanner
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        onclick="stopScanKamera()"></button>
            </div>
            <div class="modal-body p-3 d-flex flex-column align-items-center">

               <div id="scannerWrapperModal" style="
                width:100%; max-width:320px;
                border-radius:12px; overflow:hidden;
                border:3px solid #b66dff;
                position:relative; background:#000;
                aspect-ratio: 1 / 1;
            ">
                   <video id="cameraViewModal" autoplay playsinline muted
       style="width:100%; height:100%; display:block; border-radius:9px; object-fit:cover;"></video>

                    <div id="scanLineModal" style="
                        position:absolute; left:0; right:0;
                        height:3px;
                        background: linear-gradient(90deg, transparent, #ff0000, transparent);
                        box-shadow: 0 0 8px 2px rgba(255,0,0,0.7);
                        top:50%; z-index:10; display:none;
                        animation: scanAnim 2s linear infinite;
                    "></div>

                    <div style="
                        position:absolute; inset:0;
                        display:flex; align-items:center; justify-content:center;
                        pointer-events:none; z-index:9;
                    ">
                        <div style="
                            width:180px; height:180px;
                            border:3px solid #b66dff;
                            border-radius:12px;
                            box-shadow:0 0 0 9999px rgba(0,0,0,0.35);
                            animation: pulseFrame 1.5s ease-in-out infinite;
                        "></div>
                    </div>

                    <div style="position:absolute; bottom:8px; left:0; right:0; text-align:center; z-index:11;">
                        <span class="badge badge-primary px-3 py-2" id="scanLabelModal">
                            <i class="mdi mdi-loading mdi-spin me-1"></i> Memulai kamera...
                        </span>
                    </div>
                </div>

                <p class="text-muted small text-center mt-3 mb-0">
                    <i class="mdi mdi-information-outline me-1"></i>
                    Arahkan kamera ke barcode toko
                </p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-outline-danger w-100" onclick="stopScanKamera()" data-bs-dismiss="modal">
                    <i class="mdi mdi-stop me-1"></i> Berhenti Scan
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<style>
.btn-gradient-success {
    background: linear-gradient(135deg, #00c897, #00a86b) !important;
    color: #fff !important;
    border: none !important;
}
@keyframes pulseFrame {
    0%, 100% { box-shadow: 0 0 0 9999px rgba(0,0,0,0.35), 0 0 0 3px #b66dff; }
    50%       { box-shadow: 0 0 0 9999px rgba(0,0,0,0.45), 0 0 0 5px #7c4dff; }
}
@keyframes scanAnim {
    0%   { top: 5%; }
    50%  { top: 95%; }
    100% { top: 5%; }
}
</style>

<script>
    let dataToko   = null;
    let dataLokasi = null;

    function cariToko() {
        const barcode = document.getElementById('inputBarcode').value.trim();
        if (!barcode) {
            Swal.fire({ icon: 'warning', title: 'Perhatian', text: 'Masukkan barcode terlebih dahulu!', iconColor: '#b66dff' });
            return;
        }
        fetch(`/kunjungan-toko/get-toko/${barcode}`)
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('infoToko').innerHTML =
                        '<span class="text-danger"><i class="mdi mdi-alert-circle me-1"></i>Toko tidak ditemukan!</span>';
                    dataToko = null;
                } else {
                    dataToko = data;
                    document.getElementById('infoToko').innerHTML = `
                        <div class="row text-start g-1">
                            <div class="col-5 text-muted">Nama Toko</div><div class="col-7 fw-bold">: ${data.nama_toko}</div>
                            <div class="col-5 text-muted">Latitude</div><div class="col-7">: ${data.latitude}</div>
                            <div class="col-5 text-muted">Longitude</div><div class="col-7">: ${data.longitude}</div>
                            <div class="col-5 text-muted">Accuracy</div>
                            <div class="col-7">: <span class="badge badge-gradient-success">${data.accuracy} m</span></div>
                        </div>`;
                    cekSiapSubmit();
                }
            })
            .catch(() => {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil data toko!', iconColor: '#b66dff' });
            });
    }

    function ambilLokasi() {
        const btn = document.getElementById('btnAmbilLokasi');
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Mengambil lokasi...';
        btn.disabled = true;

        getAccuratePosition(50, 20000)
            .then(pos => {
                dataLokasi = pos.coords;
                document.getElementById('infoLokasi').innerHTML = `
                    <div class="row text-start g-1">
                        <div class="col-5 text-muted">Latitude</div><div class="col-7">: ${pos.coords.latitude.toFixed(7)}</div>
                        <div class="col-5 text-muted">Longitude</div><div class="col-7">: ${pos.coords.longitude.toFixed(7)}</div>
                        <div class="col-5 text-muted">Accuracy</div>
                        <div class="col-7">: <span class="badge badge-gradient-success">${pos.coords.accuracy.toFixed(1)} m</span></div>
                    </div>`;
                btn.innerHTML = '<i class="mdi mdi-check me-1"></i> Lokasi Berhasil Didapat';
                btn.className = 'btn btn-gradient-success w-100 py-2';
                cekSiapSubmit();
            })
            .catch(err => {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak dapat mengambil lokasi: ' + err.message, iconColor: '#b66dff' });
                btn.innerHTML = '<i class="mdi mdi-map-marker me-1"></i> Ambil Lokasi GPS';
                btn.disabled = false;
            });
    }

    function cekSiapSubmit() {
        if (dataToko && dataLokasi) {
            document.getElementById('btnSubmit').disabled = false;
        }
    }

    function submitKunjungan() {
        const btn = document.getElementById('btnSubmit');
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Memproses...';
        btn.disabled = true;

        fetch('/kunjungan-toko/proses', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                barcode_toko : dataToko.barcode,
                nama_toko    : dataToko.nama_toko,
                lat_toko     : dataToko.latitude,
                lng_toko     : dataToko.longitude,
                acc_toko     : dataToko.accuracy,
                lat_sales    : dataLokasi.latitude,
                lng_sales    : dataLokasi.longitude,
                acc_sales    : dataLokasi.accuracy,
            })
        })
        .then(r => r.json())
        .then(result => {
            const div   = document.getElementById('hasilKunjungan');
            const alert = document.getElementById('alertHasil');
            div.style.display = 'block';

            if (result.status === 'DITERIMA') {
                alert.className = 'alert alert-success rounded-3';
                alert.innerHTML = `
                    <i class="mdi mdi-check-circle me-2"></i>
                    <strong>DITERIMA</strong> — Kunjungan valid!<br>
                    <small>Jarak ke toko: <strong>${result.jarak_meter} m</strong>
                    &nbsp;|&nbsp; Threshold efektif: ${result.threshold_efektif} m</small>`;
                Swal.fire({ icon: 'success', title: 'DITERIMA!',
                    text: `Jarak ${result.jarak_meter}m ≤ threshold ${result.threshold_efektif}m`,
                    iconColor: '#b66dff', timer: 3000, showConfirmButton: false });
            } else {
                alert.className = 'alert alert-danger rounded-3';
                alert.innerHTML = `
                    <i class="mdi mdi-close-circle me-2"></i>
                    <strong>DITOLAK</strong> — Kamu terlalu jauh dari toko!<br>
                    <small>Jarak ke toko: <strong>${result.jarak_meter} m</strong>
                    &nbsp;|&nbsp; Threshold efektif: ${result.threshold_efektif} m</small>`;
                Swal.fire({ icon: 'error', title: 'DITOLAK!',
                    text: `Jarak ${result.jarak_meter}m > threshold ${result.threshold_efektif}m`,
                    iconColor: '#b66dff', timer: 3000, showConfirmButton: false });
            }

            btn.innerHTML = '<i class="mdi mdi-check-circle me-1"></i> Submit Kunjungan';
            btn.disabled = false;
        })
        .catch(() => {
            Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengirim data kunjungan!', iconColor: '#b66dff' });
            btn.innerHTML = '<i class="mdi mdi-check-circle me-1"></i> Submit Kunjungan';
            btn.disabled = false;
        });
    }

    function cetakBarcode(barcode, namaToko) {
        document.getElementById('modalNamaToko').innerText = namaToko;
        document.getElementById('labelBarcode').innerText  = barcode;
        JsBarcode('#barcodeCanvas', barcode, {
            format: 'CODE128', displayValue: true, fontSize: 14, margin: 10
        });
        new bootstrap.Modal(document.getElementById('modalBarcode')).show();
    }

    function getAccuratePosition(targetAccuracy = 50, maxWait = 20000) {
        return new Promise((resolve, reject) => {
            let bestResult = null;
            const startTime = Date.now();
            const watchId = navigator.geolocation.watchPosition(
                (position) => {
                    const acc = position.coords.accuracy;
                    if (!bestResult || acc < bestResult.coords.accuracy) bestResult = position;
                    if (acc <= targetAccuracy) {
                        navigator.geolocation.clearWatch(watchId);
                        resolve(bestResult);
                    }
                    if (Date.now() - startTime >= maxWait) {
                        navigator.geolocation.clearWatch(watchId);
                        if (bestResult) resolve(bestResult);
                        else reject(new Error('Timeout, tidak dapat posisi'));
                    }
                },
                (error) => reject(error),
                { enableHighAccuracy: true, maximumAge: 0, timeout: maxWait }
            );
        });
    }

    let videoStreamModal = null;
    let animFrameModal   = null;
    let isScanningModal  = false;

    const videoModal  = document.getElementById('cameraViewModal');
    const canvasModal = document.createElement('canvas');
    const ctxModal    = canvasModal.getContext('2d');

    function bukaModalScan() {
        const modal = new bootstrap.Modal(document.getElementById('modalScanKamera'));
        modal.show();
        document.getElementById('modalScanKamera').addEventListener('shown.bs.modal', startScanKamera, { once: true });
    }

    async function startScanKamera() {
        try {
            videoStreamModal = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'environment', width: 640, height: 480 }
            });
            videoModal.srcObject = videoStreamModal;
            await videoModal.play();

            isScanningModal = true;
            document.getElementById('scanLineModal').style.display = 'block';
            setScanLabelModal('Scanning...', 'badge-success');
            scanLoopModal();
        } catch (e) {
            setScanLabelModal('Kamera tidak tersedia', 'badge-danger');
        }
    }

    function stopScanKamera() {
        isScanningModal = false;
        cancelAnimationFrame(animFrameModal);
        document.getElementById('scanLineModal').style.display = 'none';
        if (videoStreamModal) {
            videoStreamModal.getTracks().forEach(t => t.stop());
            videoStreamModal = null;
        }
        videoModal.srcObject = null;
    }

    function scanLoopModal() {
        if (!isScanningModal) return;

        if (videoModal.readyState === videoModal.HAVE_ENOUGH_DATA) {
            canvasModal.width  = videoModal.videoWidth;
            canvasModal.height = videoModal.videoHeight;
            ctxModal.drawImage(videoModal, 0, 0, canvasModal.width, canvasModal.height);

            const imageData = ctxModal.getImageData(0, 0, canvasModal.width, canvasModal.height);
            const code      = jsQR(imageData.data, canvasModal.width, canvasModal.height, {
                inversionAttempts: 'dontInvert'
            });
            if (code && code.data) {
                hasilScanModal(code.data);
                return;
            }
        }
        animFrameModal = requestAnimationFrame(scanLoopModal);
    }

    function hasilScanModal(nilai) {
        stopScanKamera();
        setScanLabelModal('Terdeteksi!', 'badge-success');
        document.getElementById('inputBarcode').value = nilai;
        const modalInst = bootstrap.Modal.getInstance(document.getElementById('modalScanKamera'));
        if (modalInst) modalInst.hide();
        setTimeout(() => cariToko(), 300);
    }

    function setScanLabelModal(text, badgeClass) {
        const el = document.getElementById('scanLabelModal');
        el.textContent = text;
        el.className   = 'badge px-3 py-2 ' + badgeClass;
    }

    @if(session('success'))
        Swal.fire({
            icon: 'success', title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 2000, showConfirmButton: false, iconColor: '#b66dff'
        });
    @endif
</script>
@endpush