@extends('kantin.vendor.layouts.app')

@section('content')

<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-shopping"></i>
        </span>
        Pesanan Lunas
    </h3>
    <a href="{{ route('kantin.vendor.scan') }}" class="btn btn-gradient-primary">
        <i class="mdi mdi-qrcode-scan me-1"></i> Scan QR Pesanan
    </a>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0" style="border-radius:12px">
            <div class="card-body">

                <h4 class="text-primary mb-4">
                    <i class="mdi mdi-clipboard-check-outline me-2"></i>
                    Daftar Pesanan Lunas
                </h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover text-center align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Pesanan</th>
                                <th>Nama Customer</th>
                                <th>Total</th>
                                <th>Metode Bayar</th>
                                <th>Waktu</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td><span class="badge badge-primary">{{ $p->kode_pesanan }}</span></td>
                                <td>{{ $p->nama }}</td>
                                <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>
                                    @if($p->metode_bayar === 'qris')
                                        <span class="badge badge-info">QRIS</span>
                                    @else
                                        <span class="badge badge-warning">Virtual Account</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($p->timestamp)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button class="btn btn-gradient-primary btn-sm"
                                            onclick="lihatDetail({{ $p->idpesanan }})">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-muted">Belum ada pesanan lunas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div id="floatingDetail" style="
    display:none;
    position:fixed;
    bottom: 30px;
    right: 30px;
    width: 380px;
    max-height: 80vh;
    overflow-y: auto;
    background:#fff;
    border-radius:16px;
    box-shadow: 0 8px 32px rgba(123,75,183,0.25), 0 2px 8px rgba(0,0,0,0.1);
    z-index: 9999;
">
    {{-- Header --}}
    <div style="
        background: linear-gradient(135deg, #7b4bb7, #b66dff);
        border-radius:16px 16px 0 0;
        padding:16px 20px;
        display:flex;
        align-items:center;
        justify-content:space-between;
        position:sticky;
        top:0;
        z-index:1;
    ">
        <div class="text-white">
            <i class="mdi mdi-receipt me-2"></i>
            <strong>Detail Pesanan</strong>
        </div>
        <button onclick="tutupDetail()" style="
            background:rgba(255,255,255,0.2);
            border:none;
            color:#fff;
            border-radius:50%;
            width:30px; height:30px;
            cursor:pointer;
            font-size:1rem;
            line-height:1;
        ">✕</button>
    </div>

    <div id="isiDetail" style="padding:20px">
        <p class="text-center text-muted">Memuat...</p>
    </div>
</div>


<div id="floatingOverlay" onclick="tutupDetail()" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.3);
    z-index:9998;
"></div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function lihatDetail(idpesanan) {
    document.getElementById('floatingOverlay').style.display = 'block';
    document.getElementById('floatingDetail').style.display  = 'block';
    document.getElementById('isiDetail').innerHTML =
        '<p class="text-center text-muted py-3"><span class="spinner-border spinner-border-sm text-primary me-2"></span>Memuat...</p>';

    @foreach($pesanan as $p)
    if (idpesanan === {{ $p->idpesanan }}) {
        let html = '';

        // Info pesanan
        html += '<div style="background:#f8f4ff;border-radius:10px;padding:12px 16px;margin-bottom:16px">';
        html += '<div class="d-flex justify-content-between mb-1">';
        html += '<span class="text-muted small">Kode Pesanan</span>';
        html += '<span class="badge badge-primary">{{ $p->kode_pesanan }}</span>';
        html += '</div>';
        html += '<div class="d-flex justify-content-between mb-1">';
        html += '<span class="text-muted small">Customer</span>';
        html += '<strong>{{ $p->nama }}</strong>';
        html += '</div>';
        html += '<div class="d-flex justify-content-between">';
        html += '<span class="text-muted small">Waktu</span>';
        html += '<span class="small">{{ \Carbon\Carbon::parse($p->timestamp)->format("d/m/Y H:i") }}</span>';
        html += '</div>';
        html += '</div>';

        // Tabel menu
        html += '<table class="table table-sm mb-0">';
        html += '<thead><tr><th>Menu</th><th class="text-center">Qty</th><th class="text-end">Subtotal</th></tr></thead>';
        html += '<tbody>';
        @foreach($p->details as $d)
        html += '<tr>';
        html += '<td>{{ $d->menu->nama_menu ?? "-" }}</td>';
        html += '<td class="text-center">{{ $d->jumlah }}</td>';
        html += '<td class="text-end text-success fw-bold">Rp {{ number_format($d->subtotal, 0, ",", ".") }}</td>';
        html += '</tr>';
        @endforeach
        html += '</tbody></table>';

        // Total
        html += '<div style="border-top:2px solid #f0e8ff;margin-top:12px;padding-top:12px" class="d-flex justify-content-between align-items-center">';
        html += '<strong class="text-muted">Total</strong>';
        html += '<strong class="text-success fs-5">Rp {{ number_format($p->total, 0, ",", ".") }}</strong>';
        html += '</div>';

        document.getElementById('isiDetail').innerHTML = html;
    }
    @endforeach
}

function tutupDetail() {
    document.getElementById('floatingDetail').style.display  = 'none';
    document.getElementById('floatingOverlay').style.display = 'none';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') tutupDetail();
});
</script>

<style>
.table thead th {
    border: none !important;
    font-weight: bold;
    color: #7b4bb7;
}

#floatingDetail {
    animation: slideUp 0.3s ease;
}

#floatingOverlay {
    animation: fadeIn 0.2s ease;
}

@keyframes slideUp {
    from { opacity:0; transform: translateY(30px); }
    to   { opacity:1; transform: translateY(0); }
}

@keyframes fadeIn {
    from { opacity:0; }
    to   { opacity:1; }
}

#floatingDetail::-webkit-scrollbar { width:5px; }
#floatingDetail::-webkit-scrollbar-track { background:#f8f4ff; }
#floatingDetail::-webkit-scrollbar-thumb { background:#b66dff; border-radius:10px; }
</style>

@endsection