@extends('kantin.vendor.layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-qrcode-scan"></i>
        </span>
        Scan QR Pesanan
    </h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('kantin.vendor.dashboard') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Scan QR</li>
        </ol>
    </nav>
</div>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="row">

            {{-- scanner --}}
            <div class="col-md-5 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius:16px; overflow:hidden">
                    <div class="card-header bg-gradient-primary text-white text-center py-3 border-0">
                        <h5 class="mb-0">
                            <i class="mdi mdi-camera me-2"></i>Kamera Scanner
                        </h5>
                    </div>
                    <div class="card-body p-3 d-flex flex-column align-items-center">

                        {{-- Video Preview --}}
                        <div id="scannerWrapper" style="
                            width:100%; max-width:320px;
                            border-radius:12px; overflow:hidden;
                            border:3px solid #b66dff;
                            position:relative; background:#000;
                            min-height:280px;
                        ">
                            <video id="cameraView" autoplay playsinline muted
                                   style="width:100%; display:block; border-radius:9px"></video>

                            <div id="scanLine" style="
                                position:absolute; left:0; right:0;
                                height:3px;
                                background: linear-gradient(90deg, transparent, #ff0000, transparent);
                                box-shadow: 0 0 8px 2px rgba(255,0,0,0.7);
                                top:50%;
                                z-index:10;
                                display:none;
                                animation: scanAnim 2s linear infinite;
                            "></div>

                            <div id="scanFrame" style="
                                position:absolute; inset:0;
                                display:flex; align-items:center; justify-content:center;
                                pointer-events:none;
                                z-index:9;
                            ">
                                <div style="
                                    width:180px; height:180px;
                                    border:3px solid #b66dff;
                                    border-radius:12px;
                                    box-shadow:0 0 0 9999px rgba(0,0,0,0.35);
                                    animation: pulseFrame 1.5s ease-in-out infinite;
                                "></div>
                            </div>

                            {{-- Status label --}}
                            <div id="scanStatus" style="
                                position:absolute; bottom:8px; left:0; right:0;
                                text-align:center; z-index:11;
                            ">
                                <span class="badge badge-primary px-3 py-2" id="scanLabel">
                                    <i class="mdi mdi-loading mdi-spin me-1"></i> Memulai kamera...
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 text-center">
                            <button id="btnStartScan" class="btn btn-gradient-primary me-2" onclick="startScan()">
                                <i class="mdi mdi-play me-1"></i> Mulai Scan
                            </button>
                            <button id="btnStopScan" class="btn btn-outline-danger d-none" onclick="stopScan()">
                                <i class="mdi mdi-stop me-1"></i> Berhenti
                            </button>
                        </div>

                        <p class="text-muted small text-center mt-3 mb-0">
                            <i class="mdi mdi-information-outline me-1"></i>
                            Arahkan kamera ke QR Code pesanan customer
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-7 mb-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius:16px; overflow:hidden">
                    <div class="card-header bg-gradient-success text-white text-center py-3 border-0">
                        <h5 class="mb-0">
                            <i class="mdi mdi-receipt me-2"></i>Detail Pesanan
                        </h5>
                    </div>
                    <div class="card-body p-4">

                        <div id="stateBelumScan" class="text-center py-5">
                            <i class="mdi mdi-qrcode-scan mdi-48px text-muted d-block mb-3"
                               style="opacity:.4"></i>
                            <p class="text-muted">Belum ada QR Code yang di-scan</p>
                            <p class="text-muted small">Klik "Mulai Scan" lalu arahkan kamera</p>
                        </div>

                        <div id="stateLoading" class="text-center py-5 d-none">
                            <div class="spinner-border text-primary mb-3" style="width:3rem;height:3rem"></div>
                            <p class="text-muted">Memproses QR Code...</p>
                        </div>

                        <div id="stateError" class="d-none">
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="mdi mdi-alert-circle mdi-24px me-3"></i>
                                <div id="errorMsg">Terjadi kesalahan</div>
                            </div>
                            <div class="text-center mt-3">
                                <button class="btn btn-gradient-primary" onclick="resetScan()">
                                    <i class="mdi mdi-refresh me-1"></i> Scan Ulang
                                </button>
                            </div>
                        </div>

                        <div id="stateHasil" class="d-none">

                            <div class="alert alert-success border-0 mb-3 d-flex align-items-center"
                                 style="border-radius:10px">
                                <i class="mdi mdi-check-circle mdi-24px me-3"></i>
                                <div>
                                    <div class="fw-bold" id="res_nama"></div>
                                    <small id="res_kode" class="opacity-75"></small>
                                </div>
                                <div class="ms-auto text-end">
                                    <span id="res_status_badge" class="badge fs-6"></span>
                                </div>
                            </div>

                            {{-- Waktu pesanan --}}
                            <div class="d-flex justify-content-between text-muted small mb-3">
                                <span><i class="mdi mdi-clock me-1"></i> <span id="res_waktu"></span></span>
                            </div>

                            {{-- Tabel menu --}}
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr style="background:#f8f4ff">
                                            <th>Menu</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="res_items"></tbody>
                                    <tfoot>
                                        <tr class="fw-bold">
                                            <td colspan="2" class="text-end text-muted">Total:</td>
                                            <td class="text-end text-success" id="res_total"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="text-center mt-3">
                                <button class="btn btn-gradient-primary" onclick="resetScan()">
                                    <i class="mdi mdi-qrcode-scan me-1"></i> Scan Berikutnya
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Audio Beep --}}
<audio id="beepSound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let videoStream   = null;
let animFrame     = null;
let isScanning    = false;
let isProcessing  = false;

const video       = document.getElementById('cameraView');
const scanLabel   = document.getElementById('scanLabel');
const scanLine    = document.getElementById('scanLine');
const canvas      = document.createElement('canvas');
const ctx         = canvas.getContext('2d');

async function startScan() {
    try {
        videoStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment', width: 640, height: 480 }
        });
        video.srcObject = videoStream;
        await video.play();

        isScanning   = true;
        isProcessing = false;

        scanLine.style.display = 'block';

        document.getElementById('btnStartScan').classList.add('d-none');
        document.getElementById('btnStopScan').classList.remove('d-none');

        setLabel('Scanning...', 'badge-success');
        showState('belumScan');
        scanLoop();

    } catch (e) {
        setLabel('Kamera tidak tersedia', 'badge-danger');
        console.error(e);
    }
}

function stopScan() {
    isScanning = false;
    cancelAnimationFrame(animFrame);

    scanLine.style.display = 'none';

    if (videoStream) {
        videoStream.getTracks().forEach(t => t.stop());
        videoStream = null;
    }
    video.srcObject = null;
    document.getElementById('btnStartScan').classList.remove('d-none');
    document.getElementById('btnStopScan').classList.add('d-none');
    setLabel('Kamera mati', 'badge-secondary');
}

function scanLoop() {
    if (!isScanning) return;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {
        canvas.width  = video.videoWidth;
        canvas.height = video.videoHeight;
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code      = jsQR(imageData.data, canvas.width, canvas.height, {
            inversionAttempts: 'dontInvert'
        });

        if (code && !isProcessing) {
            handleScanResult(code.data);
            return;
        }
    }

    animFrame = requestAnimationFrame(scanLoop);
}

function handleScanResult(rawData) {
    console.log('QR terbaca:', rawData);

    const idpesanan = rawData.trim();
    if (!idpesanan) {
        tampilError('QR Code tidak valid.');
        return;
    }

    isProcessing = true;
    isScanning   = false;
    cancelAnimationFrame(animFrame);

    playBeep();
    stopScan();
    setLabel('QR Terdeteksi!', 'badge-success');
    showState('loading');

    axios.post('{{ route("kantin.vendor.scan.process") }}', { idpesanan: idpesanan })
        .then(res => {
            if (res.data.success) {
                tampilHasil(res.data);
            } else {
                tampilError(res.data.message);
            }
        })
        .catch(() => tampilError('Gagal menghubungi server. Coba lagi.'));
}

function tampilHasil(data) {
    document.getElementById('res_nama').textContent  = data.nama_customer;
    document.getElementById('res_kode').textContent  = data.kode_pesanan;
    document.getElementById('res_waktu').textContent = data.created_at;

    const badge  = document.getElementById('res_status_badge');
    const isPaid = ['paid', 'settlement'].includes(data.status_bayar);
    badge.textContent = isPaid ? '✅ LUNAS' : '⏳ BELUM BAYAR';
    badge.className   = 'badge fs-6 ' + (isPaid ? 'badge-success' : 'badge-warning');

    const tbody = document.getElementById('res_items');
    tbody.innerHTML = data.items.map(item => `
        <tr>
            <td>${item.nama_menu}</td>
            <td class="text-center">${item.jumlah}</td>
            <td class="text-end text-success fw-bold">
                Rp ${formatRupiah(item.subtotal)}
            </td>
        </tr>
    `).join('');

    document.getElementById('res_total').textContent = 'Rp ' + formatRupiah(data.total);
    showState('hasil');
}

function tampilError(msg) {
    document.getElementById('errorMsg').textContent = msg;
    showState('error');
}

function resetScan() {
    isProcessing = false;
    showState('belumScan');
    setLabel('Siap scan', 'badge-primary');
    startScan();
}

function showState(state) {
    document.getElementById('stateBelumScan').classList.add('d-none');
    document.getElementById('stateLoading').classList.add('d-none');
    document.getElementById('stateError').classList.add('d-none');
    document.getElementById('stateHasil').classList.add('d-none');

    const map = {
        belumScan : 'stateBelumScan',
        loading   : 'stateLoading',
        error     : 'stateError',
        hasil     : 'stateHasil',
    };
    if (map[state]) document.getElementById(map[state]).classList.remove('d-none');
}

function setLabel(text, badgeClass) {
    scanLabel.textContent = text;
    scanLabel.className   = 'badge px-3 py-2 ' + badgeClass;
}

function formatRupiah(n) {
    return new Intl.NumberFormat('id-ID').format(n);
}

function playBeep() {
    const beep = document.getElementById('beepSound');
    beep.currentTime = 0;
    beep.play().catch(e => console.log('Beep error:', e));
}
</script>

<style>
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

@endsection