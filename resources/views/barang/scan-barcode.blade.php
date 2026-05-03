@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-barcode-scan"></i>
        </span>
        <span class="text-muted" style="font-size: 0.8rem; font-weight: normal;">Manajemen Data /</span> Scanner Barcode ATK
    </h3>
    <nav aria-label="breadcrumb">
        <a href="{{ route('barang.index') }}" class="btn btn-gradient-primary btn-sm shadow-sm" style="border-radius: 8px;">
            <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Barang
        </a>
    </nav>
</div>

<div class="row">
    {{-- Kamera --}}
    <div class="col-md-7 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
            <div class="card-body text-center">
                <h4 class="card-title text-primary mb-3">
                    <i class="mdi mdi-camera me-2"></i>Scan Barcode Label
                </h4>

                <div id="reader-wrapper" style="position: relative; width: 100%; max-width: 480px; margin: 0 auto; border-radius: 12px; overflow: hidden; border: 2px solid #b66dff;">
                    <video id="video" style="width: 100%; display: block;"></video>
                    <div id="scan-line" style="
                        position: absolute; left: 0; right: 0;
                        height: 2px; background: #b66dff;
                        top: 50%; animation: scanAnim 2s linear infinite;
                        box-shadow: 0 0 8px #b66dff; display: none;
                    "></div>
                </div>

                <div class="mt-3 d-flex justify-content-center gap-2">
                    <button id="btnStart" class="btn btn-gradient-primary btn-sm shadow-sm" style="border-radius: 8px;">
                        <i class="mdi mdi-play me-1"></i> Mulai Scan
                    </button>
                    <button id="btnStop" class="btn btn-gradient-danger btn-sm shadow-sm" style="border-radius: 8px; display:none;">
                        <i class="mdi mdi-stop me-1"></i> Stop
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Hasil Scan --}}
    <div class="col-md-5 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius: 15px; display:none;" id="cardHasil">
            <div class="card-body">
                <h4 class="card-title text-primary">
                    <i class="mdi mdi-tag me-2"></i>Hasil Scan
                </h4>
                <hr>
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:130px;">ID Barang</td>
                       <td><strong id="hasil-id" class="text-dark">-</strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Barang</td>
                        <td class="font-weight-bold text-dark" id="hasil-nama">-</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Harga</td>
                        <td><label class="badge badge-gradient-success" id="hasil-harga">-</label></td>
                    </tr>
                </table>
                <div class="d-grid mt-3">
                    <button id="btnScanLagi" class="btn btn-gradient-primary btn-sm shadow-sm" style="border-radius:8px;">
                        <i class="mdi mdi-barcode-scan me-1"></i> Scan Lagi
                    </button>
                </div>
            </div>
        </div>

        {{-- Barang tidak ditemukan --}}
        <div class="card shadow-sm border-0" id="cardNotFound" style="border-radius:15px; display:none; border: 1px solid #fe7c96 !important;">
            <div class="card-body text-center">
                <i class="mdi mdi-alert-circle mdi-36px text-danger"></i>
                <p class="mt-2 mb-1 font-weight-bold text-danger">Barang tidak ditemukan!</p>
                <small class="text-muted" id="hasil-raw"></small>
                <div class="d-grid mt-3">
                    <button id="btnScanLagi2" class="btn btn-inverse-danger btn-sm" style="border-radius:8px;">
                        <i class="mdi mdi-refresh me-1"></i> Coba Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Audio beep --}}
<audio id="beep" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

<style>
@keyframes scanAnim {
    0%   { top: 10%; }
    50%  { top: 90%; }
    100% { top: 10%; }
}
</style>

<script src="https://unpkg.com/@zxing/library@0.19.1/umd/index.min.js"></script>
<script>
    const codeReader = new ZXing.BrowserMultiFormatReader();
    let scanning = false;

    const btnStart     = document.getElementById('btnStart');
    const btnStop      = document.getElementById('btnStop');
    const cardHasil    = document.getElementById('cardHasil');
    const cardNotFound = document.getElementById('cardNotFound');
    const scanLine     = document.getElementById('scan-line');

    function startScan() {
        scanning = true;
        btnStart.style.display     = 'none';
        btnStop.style.display      = '';
        scanLine.style.display     = '';
        cardHasil.style.display    = 'none';
        cardNotFound.style.display = 'none';

        codeReader.decodeFromVideoDevice(null, 'video', (result, err) => {
            if (result && scanning) {
                scanning = false;
                stopScan();
                onBarcodeDetected(result.getText());
            }
        });
    }

    function stopScan() {
        codeReader.reset();
        btnStart.style.display = '';
        btnStop.style.display  = 'none';
        scanLine.style.display = 'none';
    }

    function playBeep() {
        const beep = document.getElementById('beep');
        beep.currentTime = 0;
        beep.play().catch(() => {
            const ctx  = new (window.AudioContext || window.webkitAudioContext)();
            const osc  = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.frequency.value = 880;
            gain.gain.setValueAtTime(0.5, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.15);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.15);
        });
    }

    function onBarcodeDetected(code) {
        playBeep();

        fetch(`/barang/scan/${encodeURIComponent(code)}`)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('hasil-id').textContent    = data.id_barang;
                    document.getElementById('hasil-nama').textContent  = data.nama;
                    document.getElementById('hasil-harga').textContent = 'Rp ' + data.harga;
                    cardHasil.style.display    = '';
                    cardNotFound.style.display = 'none';
                } else {
                    document.getElementById('hasil-raw').textContent = 'Kode terbaca: ' + code;
                    cardNotFound.style.display = '';
                    cardHasil.style.display    = 'none';
                }
            })
            .catch(() => {
                document.getElementById('hasil-raw').textContent = 'Kode terbaca: ' + code;
                cardNotFound.style.display = '';
                cardHasil.style.display    = 'none';
            });
    }

    btnStart.addEventListener('click', startScan);
    btnStop.addEventListener('click', stopScan);
    document.getElementById('btnScanLagi').addEventListener('click', startScan);
    document.getElementById('btnScanLagi2').addEventListener('click', startScan);
</script>
@endsection