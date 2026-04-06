@extends('kantin.vendor.layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-shopping"></i>
        </span>
        Pesanan Lunas
    </h3>
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

{{-- Modal Detail --}}
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">
                    <i class="mdi mdi-receipt me-2"></i>Detail Pesanan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="isiDetail">
                <p class="text-center text-muted">Memuat...</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
function lihatDetail(idpesanan) {
    document.getElementById('isiDetail').innerHTML = '<p class="text-center text-muted">Memuat...</p>';
    const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
    modal.show();

    axios.get('/kantin/payment/status/' + idpesanan)
        .then(res => {
            // ambil detail dari pesanan
        });

    // Tampilkan detail dari data yang sudah di-load
    @foreach($pesanan as $p)
    if (idpesanan === {{ $p->idpesanan }}) {
        let html = '<table class="table table-sm">';
        html += '<thead><tr><th>Menu</th><th>Jumlah</th><th>Harga</th><th>Subtotal</th></tr></thead><tbody>';
        @foreach($p->details as $d)
        html += '<tr>';
        html += '<td>{{ $d->menu->nama_menu ?? "-" }}</td>';
        html += '<td>{{ $d->jumlah }}</td>';
        html += '<td>Rp {{ number_format($d->harga, 0, ",", ".") }}</td>';
        html += '<td>Rp {{ number_format($d->subtotal, 0, ",", ".") }}</td>';
        html += '</tr>';
        @endforeach
        html += '</tbody></table>';
        html += '<hr><strong>Total: Rp {{ number_format($p->total, 0, ",", ".") }}</strong>';
        document.getElementById('isiDetail').innerHTML = html;
    }
    @endforeach
}
</script>

<style>
.table thead th {
    border: none !important;
    font-weight: bold;
    color: #7b4bb7;
}
</style>

@endsection