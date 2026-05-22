<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Papan Antrian</title>
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=FREE"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #1a237e, #283593);
            color: white;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background: rgba(0,0,0,0.3);
            text-align: center;
            padding: 20px;
        }
        header h1 { font-size: 2rem; letter-spacing: 2px; }
        header p { opacity: 0.7; font-size: 0.9rem; }

        .main-content {
            flex: 1;
            display: flex;
            gap: 20px;
            padding: 30px;
        }

        .panel-dipanggil {
            flex: 1;
            background: rgba(255,255,255,0.1);
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
        }
        .panel-dipanggil .label { font-size: 1.2rem; opacity: 0.8; margin-bottom: 10px; }
        .panel-dipanggil .nomor {
            font-size: 10rem;
            font-weight: 900;
            color: #ffd54f;
            line-height: 1;
            text-shadow: 0 0 30px rgba(255,213,79,0.5);
        }
        .panel-dipanggil .nama { font-size: 2.5rem; margin-top: 10px; }
        .panel-dipanggil .status-badge {
            background: #4caf50;
            padding: 8px 24px;
            border-radius: 50px;
            font-size: 1rem;
            margin-top: 16px;
        }
        .panel-dipanggil .info-badge {
            display: flex;
            gap: 12px;
            margin-top: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .panel-dipanggil .info-badge span {
            background: rgba(255,255,255,0.2);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 1rem;
        }

        .panel-antrian {
            width: 340px;
            background: rgba(255,255,255,0.1);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .panel-antrian .panel-title {
            background: rgba(0,0,0,0.3);
            padding: 16px 20px;
            font-size: 1.1rem;
            font-weight: bold;
        }
        .antrian-list { flex: 1; overflow-y: auto; }
        .antrian-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: background 0.3s;
        }
        .antrian-item:hover { background: rgba(255,255,255,0.05); }
        .antrian-item .no {
            font-size: 1.4rem;
            font-weight: bold;
            color: #ffd54f;
            width: 60px;
        }
        .antrian-item .info { flex: 1; }
        .antrian-item .info .nama-item { font-size: 1rem; }
        .antrian-item .info .estimasi-item {
            font-size: 0.75rem;
            opacity: 0.6;
            margin-top: 2px;
        }

        #overlay-aktivasi {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.85);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 999;
        }
        #overlay-aktivasi h2 { font-size: 2rem; margin-bottom: 20px; }
        #overlay-aktivasi button {
            background: #ffd54f;
            color: #1a237e;
            border: none;
            padding: 16px 48px;
            font-size: 1.4rem;
            border-radius: 50px;
            cursor: pointer;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 12px;
            background: rgba(0,0,0,0.3);
            font-size: 0.85rem;
            opacity: 0.6;
        }
    </style>
</head>
<body>

<div id="overlay-aktivasi">
    <h2>🔊 Papan Antrian Digital</h2>
    <p style="opacity:0.7; margin-bottom:20px;">Klik tombol untuk mengaktifkan layar & suara</p>
    <button onclick="aktivasi()">Aktifkan Papan Antrian</button>
</div>

<header>
    <h1><i class="mdi mdi-television"></i> Antrian Digital</h1>
    <p id="jam"></p>
</header>

<div class="main-content">

    <div class="panel-dipanggil">
        <div class="label">Nomor Yang Dipanggil</div>
        <div class="nomor" id="nomor-dipanggil">---</div>
        <div class="nama" id="nama-dipanggil">Menunggu...</div>
        <div class="status-badge" id="status-badge">Standby</div>
        <div class="info-badge">
            <span id="info-ruangan" style="display:none">
                <i class="mdi mdi-door-open"></i> <span id="teks-ruangan">-</span>
            </span>
            <span id="info-loket" style="display:none">
                <i class="mdi mdi-counter"></i> <span id="teks-loket">-</span>
            </span>
        </div>
    </div>

    <div class="panel-antrian">
        <div class="panel-title">
            <i class="mdi mdi-account-clock"></i> Antrian Menunggu
            <span id="count-menunggu" style="float:right; background:#ffd54f; color:#1a237e; padding:2px 10px; border-radius:50px; font-size:0.9rem;">0</span>
        </div>
        <div class="antrian-list" id="list-menunggu">
            <div style="padding:20px; text-align:center; opacity:0.5">Belum ada antrian</div>
        </div>
    </div>

</div>

<footer>
    Sistem Antrian Digital &mdash; Real-Time via SSE
</footer>

<audio id="audio-dingdong" src="{{ asset('assets/audio/dingdong.mp3') }}"></audio>

<script>
let sudahAktif        = false;
let nomorSebelumnya   = null;
let renderTimeout     = null;
let lastMenungguCount = -1;
let sseSource         = null;

function aktivasi() {
    sudahAktif = true;
    document.getElementById('overlay-aktivasi').style.display = 'none';
    mulaiSSE();
}

function mulaiSSE() {
    if (sseSource) {
        sseSource.close();
    }

    sseSource = new EventSource('{{ url("/api/antrian/stream") }}');

    sseSource.addEventListener('queue-update', function(e) {
        const data = JSON.parse(e.data);
        clearTimeout(renderTimeout);
        renderTimeout = setTimeout(() => renderPapan(data.list, data.sekarang), 250);
    });

    sseSource.onerror = function() {
        sseSource.close();
        setTimeout(mulaiSSE, 3000); 
    };
}

function renderPapan(list, sekarang) {
    const menunggu = list.filter(i => i.status === 'menunggu');

    if (menunggu.length !== lastMenungguCount) {
        lastMenungguCount = menunggu.length;
        document.getElementById('count-menunggu').textContent = menunggu.length;

        const listEl = document.getElementById('list-menunggu');
        if (menunggu.length === 0) {
            listEl.innerHTML = '<div style="padding:20px; text-align:center; opacity:0.5">Tidak ada antrian</div>';
        } else {
            listEl.innerHTML = menunggu.map((item, idx) => {
                const estimasi = (idx + 1) * 5;
                return `
                    <div class="antrian-item">
                        <div class="no">${String(item.nomor).padStart(3,'0')}</div>
                        <div class="info">
                            <div class="nama-item">${item.nama}</div>
                            <div class="estimasi-item"><i class="mdi mdi-clock-outline"></i> ~${estimasi} menit</div>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    if (sekarang) {
        const nomorBaru = sekarang.nomor;
        if (nomorSebelumnya !== nomorBaru) {
            nomorSebelumnya = nomorBaru;

            document.getElementById('nomor-dipanggil').textContent = String(nomorBaru).padStart(3,'0');
            document.getElementById('nama-dipanggil').textContent  = sekarang.nama;
            document.getElementById('status-badge').textContent    = '✅ Silakan Masuk';

            if (sekarang.ruangan) {
                document.getElementById('teks-ruangan').textContent   = sekarang.ruangan;
                document.getElementById('info-ruangan').style.display = 'inline';
            }
            if (sekarang.loket) {
                document.getElementById('teks-loket').textContent   = sekarang.loket;
                document.getElementById('info-loket').style.display = 'inline';
            }

            bunyikanPanggilan(nomorBaru, sekarang.nama, sekarang.ruangan, sekarang.loket);
        }
    } else {
        document.getElementById('nomor-dipanggil').textContent = '---';
        document.getElementById('nama-dipanggil').textContent  = 'Menunggu...';
        document.getElementById('status-badge').textContent    = 'Standby';
        document.getElementById('info-ruangan').style.display  = 'none';
        document.getElementById('info-loket').style.display    = 'none';
    }
}

function angkaKeKata(n) {
    const satuan  = ['', 'satu', 'dua', 'tiga', 'empat', 'lima',
                     'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh',
                     'sebelas', 'dua belas', 'tiga belas', 'empat belas', 'lima belas',
                     'enam belas', 'tujuh belas', 'delapan belas', 'sembilan belas'];
    const puluhan = ['', '', 'dua puluh', 'tiga puluh', 'empat puluh', 'lima puluh',
                     'enam puluh', 'tujuh puluh', 'delapan puluh', 'sembilan puluh'];
    if (n < 20)   return satuan[n];
    if (n < 100)  return puluhan[Math.floor(n/10)] + (n % 10 !== 0 ? ' ' + satuan[n % 10] : '');
    if (n < 200)  return 'seratus' + (n % 100 !== 0 ? ' ' + angkaKeKata(n % 100) : '');
    if (n < 1000) return satuan[Math.floor(n/100)] + ' ratus' + (n % 100 !== 0 ? ' ' + angkaKeKata(n % 100) : '');
    return n.toString();
}

function bicarakanTeks(teks) {
    // Coba responsiveVoice dulu
    if (typeof responsiveVoice !== 'undefined' && responsiveVoice.voiceSupport()) {
        responsiveVoice.speak(teks, 'Indonesian Female', {
            rate: 0.9,
            pitch: 1,
            volume: 1
        });
        return;
    }

    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel();
        const pesan = new SpeechSynthesisUtterance(teks);
        pesan.lang   = 'id-ID';
        pesan.rate   = 0.85;
        pesan.pitch  = 1.0;
        pesan.volume = 1.0;
        window.speechSynthesis.speak(pesan);
    }
}

function bunyikanPanggilan(nomor, nama, ruangan, loket) {
    if (!sudahAktif) return;

    function konversiAngka(teks) {
    return teks.replace(/\d+/g, n => angkaKeKata(parseInt(n)));
}

    const nomorKata   = angkaKeKata(nomor);
    const loketTeks   = loket   ? ` ${konversiAngka(loket)}.`                    : '';
    const ruanganTeks = ruangan ? ` Silakan masuk ke ${konversiAngka(ruangan)}.` : ' Silakan masuk.';
    const teks = `Nomor antrian ${nomorKata}. ${nama}.${ruanganTeks}${loketTeks}`;

    const audio = document.getElementById('audio-dingdong');
    audio.currentTime = 0;
    audio.onended = null;

    audio.play().then(() => {
        audio.onended = function () {
            audio.onended = null;
            bicarakanTeks(teks);
        };
    }).catch(() => {
        // Audio gagal, langsung bicara
        bicarakanTeks(teks);
    });
}

function updateJam() {
    const now = new Date();
    document.getElementById('jam').textContent = now.toLocaleTimeString('id-ID', {
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
}
setInterval(updateJam, 1000);
updateJam();
</script>
</body>
</html>