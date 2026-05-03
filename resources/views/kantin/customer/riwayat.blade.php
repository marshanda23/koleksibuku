@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-history"></i>
        </span>
        Riwayat Pesanan
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">

        <div class="card border-0 shadow-sm mb-4" style="border-radius:14px">
            <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
                <div>
                    <small class="text-muted">Pesanan untuk</small>
                    <h5 class="mb-0 fw-bold text-primary">{{ $nama }}</h5>
                </div>
                <a href="{{ route('kantin.order') }}" class="btn btn-gradient-primary btn-sm">
                    <i class="mdi mdi-plus me-1"></i> Pesan Baru
                </a>
            </div>
        </div>

        @forelse($pesananList as $p)
        <div class="card border-0 shadow-sm mb-3" style="border-radius:14px; overflow:hidden">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <span class="badge badge-primary mb-1">{{ $p->kode_pesanan }}</span>
                        <div class="text-muted small">
                            {{ \Carbon\Carbon::parse($p->timestamp)->format('d M Y, H:i') }}
                        </div>
                    </div>
                    <div class="text-end">
                        {{-- status_bayar: 0=pending, 1=lunas, 2=gagal --}}
                        @if((int)$p->status_bayar === 1)
                            <span class="badge badge-success">
                                <i class="mdi mdi-check-circle me-1"></i>Lunas
                            </span>
                        @elseif((int)$p->status_bayar === 2)
                            <span class="badge badge-danger">
                                <i class="mdi mdi-close-circle me-1"></i>Gagal
                            </span>
                        @else
                            <span class="badge badge-warning">
                                <i class="mdi mdi-clock me-1"></i>Pending
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    @foreach($p->detailPesanan as $d)
                    <div class="d-flex justify-content-between small py-1 border-bottom">
                        <span>{{ $d->menu->nama_menu ?? '-' }} x{{ $d->jumlah }}</span>
                        <span class="text-success fw-bold">
                            Rp {{ number_format($d->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                    @endforeach
                    <div class="d-flex justify-content-between mt-2">
                        <strong>Total</strong>
                        <strong class="text-success">
                            Rp {{ number_format($p->total, 0, ',', '.') }}
                        </strong>
                    </div>
                </div>
                
                @if((int)$p->status_bayar === 1)
                <div class="text-center border-top pt-3">
                    <p class="text-muted small mb-2">
                        <i class="mdi mdi-qrcode me-1"></i> Tunjukkan QR Code ini ke kasir
                    </p>
                    <img src="{{ route('kantin.qr', $p->idpesanan) }}"
                         alt="QR Code"
                         style="width:160px;height:160px;border:1px solid #eee;
                                border-radius:10px;padding:4px;background:#fff">
                    <div class="mt-2">
                        <a href="{{ route('kantin.qr', $p->idpesanan) }}"
                           download="qr-{{ $p->kode_pesanan }}.png"
                           class="btn btn-outline-primary btn-sm">
                            <i class="mdi mdi-download me-1"></i> Download QR
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
        @empty
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius:14px">
            <div class="card-body">
                <i class="mdi mdi-receipt mdi-48px text-muted mb-3 d-block"></i>
                <h5 class="text-muted">Belum ada pesanan</h5>
                <a href="{{ route('kantin.order') }}" class="btn btn-gradient-primary mt-2">
                    Pesan Sekarang
                </a>
            </div>
        </div>
        @endforelse

    </div>
</div>

@endsection