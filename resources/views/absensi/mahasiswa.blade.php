@extends('layouts.app')

@section('content')

<style>
    .header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
    .header h1 { font-size: 22px; font-weight: 700; color: #3b0764; }
    .header p { font-size: 13px; color: #7c3aed; margin-top: 4px; }

    .header-actions { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

    .btn-back {
        padding: 8px 18px; border-radius: 99px;
        background: white; border: 1.5px solid #c4b5fd;
        color: #6d28d9; font-size: 13px; font-weight: 500;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-back:hover { background: #7c3aed; color: white; border-color: #7c3aed; }

    .btn-tambah {
        padding: 8px 18px; border-radius: 99px;
        background: #7c3aed; border: 1.5px solid #7c3aed;
        color: white; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
    }
    .btn-tambah:hover { background: #6d28d9; border-color: #6d28d9; }

    .card {
        background: white; border-radius: 16px;
        border: 1px solid #e9d5ff;
        box-shadow: 0 2px 12px rgba(124,58,237,0.08);
        overflow: hidden; margin-bottom: 24px;
    }
    .card-header {
        padding: 14px 20px;
        border-bottom: 1px solid #f3e8ff;
        background: linear-gradient(135deg, #faf5ff, #f3e8ff);
        display: flex; align-items: center; justify-content: space-between;
    }
    .card-header h2 { font-size: 14px; font-weight: 700; color: #3b0764; margin: 0; }

    .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

    table { width: 100%; border-collapse: collapse; font-size: 13px; table-layout: fixed; }
    thead th:nth-child(1) { width: 25%; }
    thead th:nth-child(2) { width: 18%; }
    thead th:nth-child(3) { width: 25%; }
    thead th:nth-child(4) { width: 15%; }
    thead th:nth-child(5) { width: 17%; }

    th {
        background: #faf5ff; padding: 12px 0;
        text-align: center; color: #7c3aed;
        font-weight: 600; font-size: 11px;
        text-transform: uppercase; letter-spacing: 0.05em;
        white-space: nowrap; border-bottom: 2px solid #5c3282;
    }
    td {
        padding: 14px 0; border-top: 1px solid #f3e8ff;
        color: #374151; text-align: center;
        overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
    }

    .serial-text { font-family: monospace; font-size: 11px; color: #7c3aed; display: inline-block; }
    .badge-terdaftar { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; background: #ede9fe; color: #6d28d9; }
    .badge-belum { display: inline-block; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; background: #fef2f2; color: #b91c1c; }

    .btn-daftar { padding: 5px 10px; border-radius: 99px; border: 1.5px solid #c4b5fd; background: #faf5ff; color: #6d28d9; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.15s; white-space: nowrap; margin: 0 2px; }
    .btn-daftar:hover { background: #7c3aed; color: white; border-color: #7c3aed; }

    .btn-hapus { padding: 5px 10px; border-radius: 99px; border: 1.5px solid #fca5a5; background: #fef2f2; color: #b91c1c; font-size: 11px; font-weight: 600; cursor: pointer; transition: all 0.15s; white-space: nowrap; margin: 0 2px; }
    .btn-hapus:hover { background: #b91c1c; color: white; border-color: #b91c1c; }

    /* Modal */
    .my-modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.65);
        z-index: 99999;
        align-items: center;
        justify-content: center;
    }
    .my-modal-overlay.show { display: flex; }
    .my-modal {
        background: white;
        border-radius: 20px;
        padding: 28px 24px;
        width: 90%; max-width: 400px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.5);
        text-align: center;
    }
    .my-modal h3 { font-size: 16px; font-weight: 700; color: #3b0764; margin-bottom: 6px; }
    .my-modal p { font-size: 13px; color: #9ca3af; margin-bottom: 16px; }

    .nfc-icon-area { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #ede9fe, #ddd6fe); border: 2px dashed #7c3aed; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; }
    .nfc-icon-area i { font-size: 32px; color: #7c3aed; }

    .modal-actions { display: flex; gap: 8px; justify-content: center; margin-top: 8px; }
    .btn-modal { padding: 8px 20px; border-radius: 99px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; }
    .btn-modal.primary { background: #7c3aed; color: white; }
    .btn-modal.secondary { background: white; color: #6d28d9; border: 1.5px solid #c4b5fd; }
    .btn-modal.danger { background: #b91c1c; color: white; }
    .btn-modal.primary:hover { background: #6d28d9; }
    .btn-modal.danger:hover { background: #991b1b; }

    .form-input { width: 100%; padding: 8px 14px; border-radius: 10px; border: 1.5px solid #c4b5fd; font-size: 13px; color: #374151; margin-bottom: 12px; outline: none; text-align: left; box-sizing: border-box; }
    .form-input:focus { border-color: #7c3aed; }
    .form-label { display: block; font-size: 12px; font-weight: 600; color: #6d28d9; margin-bottom: 4px; text-align: left; }

    .serial-input { width: 100%; padding: 8px 14px; border-radius: 10px; border: 1.5px solid #c4b5fd; font-size: 13px; color: #374151; margin-bottom: 12px; outline: none; box-sizing: border-box; }
    .serial-input:focus { border-color: #7c3aed; }

    .msg-box { display:none; padding:10px 14px; border-radius:10px; font-size:13px; text-align:left; margin-bottom:12px; }
    .msg-box.ok  { background:#f0fdf4; border:1.5px solid #86efac; color:#15803d; }
    .msg-box.err { background:#fef2f2; border:1.5px solid #fca5a5; color:#b91c1c; }
</style>

<div class="header">
    <div>
        <h1><i class="mdi mdi-account-group"></i> Daftar Mahasiswa</h1>
        <p>Kelola kartu NFC mahasiswa</p>
    </div>
    <div class="header-actions">
        <button class="btn-tambah" onclick="bukaTambah()">
            <i class="mdi mdi-account-plus"></i> Tambah Mahasiswa
        </button>
        <a href="/absensi" class="btn-back">
            <i class="mdi mdi-arrow-left"></i> Kembali ke Scanner
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2><i class="mdi mdi-card-account-details"></i> Data Mahasiswa & Kartu NFC</h2>
        <span style="font-size:11px; color:#7c3aed;">{{ $mahasiswas->count() }} mahasiswa</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th><th>NIM</th><th>Serial NFC</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswas as $mhs)
                <tr>
                    <td><strong>{{ $mhs->nama }}</strong></td>
                    <td>{{ $mhs->nim }}</td>
                    <td><span class="serial-text">{{ $mhs->serial_nfc ?? '-' }}</span></td>
                    <td>
                        @if($mhs->serial_nfc)
                            <span class="badge-terdaftar">✅ Terdaftar</span>
                        @else
                            <span class="badge-belum">❌ Belum ada kartu</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn-daftar" onclick="bukaKartu({{ $mhs->id }}, '{{ $mhs->nama }}')">
                            <i class="mdi mdi-nfc-tap"></i> {{ $mhs->serial_nfc ? 'Ganti' : 'Daftarkan' }}
                        </button>
                        <button class="btn-hapus" onclick="bukaHapus({{ $mhs->id }}, '{{ $mhs->nama }}')">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.insertAdjacentHTML('beforeend', `
        <!-- Modal Daftarkan Kartu -->
        <div class="my-modal-overlay" id="modal-kartu">
            <div class="my-modal">
                <div class="nfc-icon-area"><i class="mdi mdi-nfc-variant"></i></div>
                <h3>Daftarkan Kartu NFC</h3>
                <p>Untuk mahasiswa: <strong id="kartu-nama"></strong></p>
                <div id="kartu-mode-nfc" style="display:none;">
                    <p id="kartu-status" style="font-size:13px;color:#9ca3af;margin-bottom:12px;">Tekan tombol lalu tempelkan kartu NFC ke HP.</p>
                    <div class="modal-actions" style="margin-bottom:12px;">
                        <button class="btn-modal primary" onclick="aktifkanNFC()"><i class="mdi mdi-nfc-tap"></i> Scan Kartu</button>
                    </div>
                </div>
                <div id="kartu-mode-sim" style="display:none;">
                    <p style="font-size:12px;color:#9ca3af;margin-bottom:8px;">Masukkan serial NFC kartu secara manual:</p>
                    <input type="text" class="serial-input" id="input-serial" placeholder="Contoh: 04:AB:CD:EF:12:34:06">
                </div>
                <div class="msg-box" id="kartu-msg"></div>
                <div class="modal-actions">
                    <button class="btn-modal secondary" onclick="tutupKartu()">Batal</button>
                    <button class="btn-modal primary" onclick="simpanKartu()"><i class="mdi mdi-content-save"></i> Simpan</button>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Mahasiswa -->
        <div class="my-modal-overlay" id="modal-tambah">
            <div class="my-modal">
                <div class="nfc-icon-area"><i class="mdi mdi-account-plus"></i></div>
                <h3>Tambah Mahasiswa Baru</h3>
                <p>Isi data mahasiswa yang akan didaftarkan</p>
                <div style="text-align:left;">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-input" id="input-nama-baru">
                    <label class="form-label">NIM</label>
                    <input type="text" class="form-input" id="input-nim-baru">
                </div>
                <div class="msg-box" id="tambah-msg"></div>
                <div class="modal-actions">
                    <button class="btn-modal secondary" onclick="tutupTambah()">Batal</button>
                    <button class="btn-modal primary" onclick="simpanMahasiswa()"><i class="mdi mdi-content-save"></i> Simpan</button>
                </div>
            </div>
        </div>

        <!-- Modal Hapus -->
        <div class="my-modal-overlay" id="modal-hapus">
            <div class="my-modal">
                <div class="nfc-icon-area" style="background:linear-gradient(135deg,#fef2f2,#fecaca);border-color:#b91c1c;">
                    <i class="mdi mdi-delete" style="color:#b91c1c;"></i>
                </div>
                <h3>Hapus Mahasiswa</h3>
                <p>Yakin ingin menghapus <strong id="hapus-nama"></strong>?<br>Data absensinya juga akan terhapus.</p>
                <div class="msg-box" id="hapus-msg"></div>
                <div class="modal-actions">
                    <button class="btn-modal secondary" onclick="tutupHapus()">Batal</button>
                    <button class="btn-modal danger" onclick="hapusMahasiswa()"><i class="mdi mdi-delete"></i> Hapus</button>
                </div>
            </div>
        </div>
    `);
});

let kartuIdAktif = null;
let serialAktif  = null;
let hapusIdAktif = null;

function bukaKartu(id, nama) {
    kartuIdAktif = id; serialAktif = null;
    document.getElementById('kartu-nama').textContent = nama;
    document.getElementById('kartu-msg').className = 'msg-box';
    document.getElementById('kartu-msg').style.display = 'none';
    if ('NDEFReader' in window) {
        document.getElementById('kartu-mode-nfc').style.display = 'block';
        document.getElementById('kartu-mode-sim').style.display = 'none';
    } else {
        document.getElementById('kartu-mode-nfc').style.display = 'none';
        document.getElementById('kartu-mode-sim').style.display = 'block';
        document.getElementById('input-serial').value = '';
    }
    document.getElementById('modal-kartu').classList.add('show');
}
function tutupKartu() { document.getElementById('modal-kartu').classList.remove('show'); }

async function aktifkanNFC() {
    const st = document.getElementById('kartu-status');
    try {
        const ndef = new NDEFReader();
        await ndef.scan();
        st.textContent = '📡 Scanner aktif — tempelkan kartu ke HP...';
        ndef.addEventListener('reading', ({ serialNumber }) => {
            serialAktif = serialNumber;
            st.textContent = '✅ Kartu terbaca: ' + serialNumber;
        });
    } catch (err) { st.textContent = 'Gagal: ' + err.message; }
}

async function simpanKartu() {
    const msg = document.getElementById('kartu-msg');
    let serial = serialAktif || document.getElementById('input-serial')?.value?.trim();
    if (!serial) { showMsg(msg, 'err', '❌ Serial belum ada.'); return; }
    try {
        const res = await fetch('/absensi/daftarkan-kartu', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf() },
            body: JSON.stringify({ mahasiswa_id: kartuIdAktif, serial_nfc: serial }),
        });
        const data = await res.json();
        showMsg(msg, data.status === 'berhasil' ? 'ok' : 'err', (data.status === 'berhasil' ? '✅ ' : '❌ ') + data.pesan);
        if (data.status === 'berhasil') setTimeout(() => { tutupKartu(); location.reload(); }, 1500);
    } catch { showMsg(msg, 'err', '❌ Kesalahan koneksi.'); }
}

function bukaTambah() {
    document.getElementById('input-nama-baru').value = '';
    document.getElementById('input-nim-baru').value = '';
    document.getElementById('tambah-msg').style.display = 'none';
    document.getElementById('modal-tambah').classList.add('show');
}
function tutupTambah() { document.getElementById('modal-tambah').classList.remove('show'); }

async function simpanMahasiswa() {
    const msg  = document.getElementById('tambah-msg');
    const nama = document.getElementById('input-nama-baru').value.trim();
    const nim  = document.getElementById('input-nim-baru').value.trim();
    if (!nama || !nim) { showMsg(msg, 'err', '❌ Nama dan NIM wajib diisi.'); return; }
    try {
        const res = await fetch('/absensi/mahasiswa/tambah', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf() },
            body: JSON.stringify({ nama, nim }),
        });
        const data = await res.json();
        showMsg(msg, data.status === 'berhasil' ? 'ok' : 'err', (data.status === 'berhasil' ? '✅ ' : '❌ ') + data.pesan);
        if (data.status === 'berhasil') setTimeout(() => { tutupTambah(); location.reload(); }, 1500);
    } catch { showMsg(msg, 'err', '❌ Kesalahan koneksi.'); }
}

function bukaHapus(id, nama) {
    hapusIdAktif = id;
    document.getElementById('hapus-nama').textContent = nama;
    document.getElementById('hapus-msg').style.display = 'none';
    document.getElementById('modal-hapus').classList.add('show');
}
function tutupHapus() { document.getElementById('modal-hapus').classList.remove('show'); }

async function hapusMahasiswa() {
    const msg = document.getElementById('hapus-msg');
    try {
        const res = await fetch('/absensi/mahasiswa/' + hapusIdAktif, {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf() },
        });
        const data = await res.json();
        showMsg(msg, data.status === 'berhasil' ? 'ok' : 'err', (data.status === 'berhasil' ? '✅ ' : '❌ ') + data.pesan);
        if (data.status === 'berhasil') setTimeout(() => { tutupHapus(); location.reload(); }, 1500);
    } catch { showMsg(msg, 'err', '❌ Kesalahan koneksi.'); }
}

function csrf() { return document.querySelector('meta[name="csrf-token"]').content; }
function showMsg(el, type, text) {
    el.className = 'msg-box ' + type;
    el.textContent = text;
    el.style.display = 'block';
}
</script>
@endpush