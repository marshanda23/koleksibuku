@extends('layouts.app')

@push('style')
<style>
    .absensi-header {
        display: flex; align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 24px; flex-wrap: wrap; gap: 12px;
    }
    .absensi-header-left h1 { font-size: 22px; font-weight: 700; color: #3b0764; margin-bottom: 4px; }
    .absensi-header-left p { font-size: 13px; color: #7c3aed; }

    .btn-kelola {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 8px 18px; border-radius: 99px;
        background: white; border: 1.5px solid #c4b5fd;
        color: #6d28d9; font-size: 13px; font-weight: 600;
        text-decoration: none; white-space: nowrap; flex-shrink: 0;
    }
    .btn-kelola:hover { background: #7c3aed; color: white; border-color: #7c3aed; }

    .stat-row { display: flex; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }
    .stat-card {
        flex: 1; min-width: 140px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        border-radius: 14px; padding: 18px 20px; color: white;
        box-shadow: 0 4px 15px rgba(124,58,237,0.3);
    }
    .stat-card.light {
        background: white; border: 1px solid #e9d5ff;
        color: #280840; box-shadow: 0 2px 8px rgba(124,58,237,0.1);
    }
    .stat-card .s-label { font-size: 11px; opacity: 0.8; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }
    .stat-card .s-value { font-size: 32px; font-weight: 700; line-height: 1; }
    .stat-card.light .s-label { color: #2e1b4f; opacity: 1; }
    .stat-card.light .s-value { color: #3b0764; }
    .stat-card .s-value.hadir { color: #4ade80; }
    .stat-card .s-value.belum { color: #fbbf24; }

    .scanner-box {
        background: white; border-radius: 16px;
        padding: 28px 24px; margin-bottom: 24px;
        border: 1px solid #e9d5ff;
        box-shadow: 0 2px 12px rgba(124,58,237,0.08);
        text-align: center;
    }
    .scanner-box h2 { font-size: 16px; font-weight: 700; color: #3b0764; margin-bottom: 6px; }
    .scanner-box .scanner-sub { font-size: 12px; color: #9ca3af; margin-bottom: 20px; }

    .nfc-icon-area {
        width: 90px; height: 90px; border-radius: 50%;
        background: linear-gradient(135deg, #ede9fe, #ddd6fe);
        border: 2px dashed #7c3aed;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
    }
    .nfc-icon-area i { font-size: 36px; color: #7c3aed; }

    .kartu-list { display: flex; gap: 8px; flex-wrap: wrap; justify-content: center; margin-bottom: 16px; }
    .kartu-btn {
        padding: 8px 16px; border-radius: 99px;
        border: 1.5px solid #c4b5fd; background: #faf5ff;
        font-size: 12px; color: #6d28d9; cursor: pointer;
        font-weight: 500; transition: all 0.15s;
    }
    .kartu-btn:hover { background: #7c3aed; color: white; border-color: #7c3aed; }

    #status, #status-sim { font-size: 13px; color: #9ca3af; margin-bottom: 10px; min-height: 20px; }

    #result-box {
        display: none; padding: 14px 18px;
        border-radius: 10px; font-size: 13px;
        margin-top: 12px; text-align: left;
        max-width: 340px; margin-left: auto; margin-right: auto;
    }
    #result-box.berhasil { background: #f0fdf4; border: 1.5px solid #86efac; color: #15803d; }
    #result-box.gagal    { background: #fef2f2; border: 1.5px solid #fca5a5; color: #b91c1c; }
    #result-box.duplikat { background: #fffbeb; border: 1.5px solid #fcd34d; color: #92400e; }

    .log-box {
        background: white; border-radius: 16px;
        border: 1px solid #e9d5ff; overflow: hidden;
        box-shadow: 0 2px 12px rgba(124,58,237,0.08);
    }
    .log-box-header {
        padding: 14px 24px; border-bottom: 1px solid #f3e8ff;
        display: flex; align-items: center; justify-content: space-between;
        background: linear-gradient(135deg, #faf5ff, #f3e8ff);
    }
    .log-box-header h2 { font-size: 14px; font-weight: 700; color: #3b0764; margin: 0; }
    .log-box-header span { font-size: 11px; color: #7c3aed; }

    .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .log-box table { width: 100%; border-collapse: collapse; font-size: 13px; table-layout: fixed; }
    .log-box thead th:nth-child(1) { width: 30%; }
    .log-box thead th:nth-child(2) { width: 25%; }
    .log-box thead th:nth-child(3) { width: 25%; }
    .log-box thead th:nth-child(4) { width: 20%; }
    .log-box th {
        background: #faf5ff; padding: 14px 0;
        text-align: center; color: #7c3aed;
        font-weight: 600; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.05em;
        white-space: nowrap; border-bottom: 2px solid #7c3aed;
    }
    .log-box td {
        padding: 16px 0; border-top: 1px solid #f3e8ff;
        color: #374151; white-space: nowrap;
        overflow: hidden; text-overflow: ellipsis; text-align: center;
    }
    .badge-hadir {
        display: inline-block; padding: 3px 12px;
        border-radius: 99px; font-size: 11px; font-weight: 600;
        background: #ede9fe; color: #6d28d9;
    }
</style>
@endpush

@section('content')
<div class="absensi-header">
    <div class="absensi-header-left">
        <h1><i class="mdi mdi-nfc-variant"></i> Sistem Absensi NFC</h1>
        <p>Universitas Airlangga &middot; {{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
    </div>
    <a href="/absensi/mahasiswa" class="btn-kelola">
        <i class="mdi mdi-account-group"></i> Kelola Kartu Mahasiswa
    </a>
</div>

<div class="stat-row">
    <div class="stat-card light">
        <div class="s-label">Total Mahasiswa</div>
        <div class="s-value">{{ $totalMahasiswa }}</div>
    </div>
    <div class="stat-card">
        <div class="s-label">Hadir Hari Ini</div>
        <div class="s-value hadir" id="count-hadir">{{ $logHariIni->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="s-label">Belum Absen</div>
        <div class="s-value belum" id="count-belum">{{ $totalMahasiswa - $logHariIni->count() }}</div>
    </div>
</div>

<div class="scanner-box">
    <div class="nfc-icon-area" id="nfc-icon-area">
        <i class="mdi mdi-nfc-variant"></i>
    </div>
    <h2>Scanner NFC</h2>

    <div id="mode-nfc-asli" style="display:none;">
        <p class="scanner-sub">Tempelkan kartu NFC ke belakang HP</p>
        <button id="btn-aktifkan" class="kartu-btn" style="font-size:13px; padding:10px 24px; margin-bottom:16px;" onclick="aktifkanNFC()">
            <i class="mdi mdi-nfc-tap"></i> Aktifkan Scanner NFC
        </button>
        <p id="status">Tekan tombol lalu tempelkan kartu.</p>
    </div>

    <div id="mode-simulasi" style="display:none;">
        <p class="scanner-sub">Simulasi: pilih kartu mahasiswa yang ingin di-scan</p>
        <div class="kartu-list" id="kartu-list"></div>
        <p id="status-sim">Pilih kartu di atas untuk simulasi scan.</p>
    </div>

    <div id="result-box"></div>
</div>

<div class="log-box">
    <div class="log-box-header">
        <h2><i class="mdi mdi-clipboard-list-outline"></i> Log Absensi Hari Ini</h2>
        <span>{{ \Carbon\Carbon::today()->format('d M Y') }}</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIM</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="log-body">
                @forelse($logHariIni as $log)
                <tr>
                    <td>{{ $log->mahasiswa->nama }}</td>
                    <td>{{ $log->mahasiswa->nim }}</td>
                    <td>{{ $log->waktu }}</td>
                    <td><span class="badge-hadir">Hadir</span></td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; color:#c4b5fd; padding:28px;">
                        <i class="mdi mdi-inbox-outline" style="font-size:24px; display:block; margin-bottom:6px;"></i>
                        Belum ada absensi hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('script')
<script>
window.addEventListener('DOMContentLoaded', () => {
    if ('NDEFReader' in window) {
        document.getElementById('mode-nfc-asli').style.display = 'block';
    } else {
        document.getElementById('mode-simulasi').style.display = 'block';
        buatTombolSimulasi();
    }
});

async function aktifkanNFC() {
    const btn    = document.getElementById('btn-aktifkan');
    const status = document.getElementById('status');
    const area   = document.getElementById('nfc-icon-area');
    try {
        const ndef = new NDEFReader();
        await ndef.scan();
        btn.textContent = '📡 Menunggu kartu...';
        btn.style.background = '#7c3aed';
        btn.style.color = 'white';
        status.textContent = 'Scanner aktif — tempelkan kartu NFC ke HP';
        area.style.borderColor = '#7c3aed';
        ndef.addEventListener('reading', async ({ serialNumber, message }) => {
            let isiRecord = '';
            for (const record of message.records) {
                isiRecord += new TextDecoder().decode(record.data);
            }
            console.log('Serial Number:', serialNumber);
            status.textContent = `Kartu terbaca: ${serialNumber}`;
            await kirimAbsensi(serialNumber, status);
        });
        ndef.addEventListener('readingerror', () => {
            status.textContent = 'Gagal membaca kartu. Coba lagi.';
        });
    } catch (err) {
        if (err.name === 'NotAllowedError') {
            status.textContent = 'Izin NFC ditolak. Mohon izinkan akses NFC.';
        } else {
            status.textContent = 'NFC tidak dapat diaktifkan: ' + err.message;
        }
    }
}

const kartuSimulasi = [
    { nama: 'Marshanda Hadi S',        serial: '04:AB:CD:EF:12:34:01' },
    { nama: 'Salsyabilla Nurul Shifa', serial: '04:AB:CD:EF:12:34:02' },
    { nama: 'Annaura Salsabilla',      serial: '04:AB:CD:EF:12:34:03' },
    { nama: 'Ciza Aferta',             serial: '04:AB:CD:EF:12:34:04' },
    { nama: 'Oliver',                  serial: '04:AB:CD:EF:12:34:05' },
];

function buatTombolSimulasi() {
    const kartuList = document.getElementById('kartu-list');
    kartuSimulasi.forEach(kartu => {
        const btn = document.createElement('button');
        btn.className = 'kartu-btn';
        btn.textContent = kartu.nama;
        btn.onclick = async () => {
            const status = document.getElementById('status-sim');
            status.textContent = `Memproses kartu ${kartu.nama}...`;
            await kirimAbsensi(kartu.serial, status);
        };
        kartuList.appendChild(btn);
    });
}

async function kirimAbsensi(serial, statusEl) {
    const resultBox = document.getElementById('result-box');
    resultBox.style.display = 'none';
    try {
        const url = window.location.origin + '/absensi/scan';
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ serial_nfc: serial }),
        });
        const data = await response.json();
        resultBox.className = 'result-box ' + data.status;
        resultBox.style.display = 'block';
        if (data.status === 'berhasil') {
            resultBox.innerHTML = `
                <strong>✅ Berhasil!</strong><br>
                Nama &nbsp;: ${data.mahasiswa.nama}<br>
                NIM &nbsp;&nbsp;: ${data.mahasiswa.nim}<br>
                Waktu : ${data.waktu}
            `;
            const tbody = document.getElementById('log-body');
            const emptyRow = tbody.querySelector('td[colspan]');
            if (emptyRow) emptyRow.closest('tr').remove();
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${data.mahasiswa.nama}</td>
                <td>${data.mahasiswa.nim}</td>
                <td>${data.waktu}</td>
                <td><span class="badge-hadir">Hadir</span></td>
            `;
            tbody.insertBefore(row, tbody.firstChild);
            const hadirEl = document.getElementById('count-hadir');
            const belumEl = document.getElementById('count-belum');
            hadirEl.textContent = parseInt(hadirEl.textContent) + 1;
            belumEl.textContent = parseInt(belumEl.textContent) - 1;
        } else {
            resultBox.innerHTML = `<strong>${data.status === 'duplikat' ? '⚠️' : '❌'}</strong> ${data.pesan}`;
        }
        statusEl.textContent = 'Selesai. ' + (data.status === 'berhasil' ? 'Tempelkan kartu berikutnya.' : '');
    } catch (err) {
        console.error('Error:', err);
        statusEl.textContent = 'Terjadi kesalahan koneksi.';
    }
}
</script>
@endpush