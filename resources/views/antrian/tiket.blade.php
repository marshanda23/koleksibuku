@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card mt-4 text-center border-primary">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="mdi mdi-ticket"></i> Tiket Antrian Kamu</h4>
            </div>
            <div class="card-body py-5">
                @if($tiket)
                    <p class="text-muted mb-1">Nomor Antrian</p>
                    <h1 class="display-1 fw-bold text-primary">
                        {{ str_pad($tiket['nomor'], 3, '0', STR_PAD_LEFT) }}
                    </h1>
                    <h4 class="mt-3">{{ $tiket['nama'] }}</h4>
                    <span class="badge bg-warning text-dark mt-2 fs-6">Menunggu Dipanggil</span>

                    @if($estimasi)
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="mdi mdi-clock-outline"></i>
                        Estimasi waktu tunggu: <strong>~{{ $estimasi }} menit</strong>
                    </div>
                    @endif

                    <hr>
                    <p class="text-muted small">Silakan tunggu, nomor kamu akan dipanggil di papan antrian.</p>
                    <a href="{{ route('antrian.guest') }}" class="btn btn-outline-primary mt-2">
                        <i class="mdi mdi-plus"></i> Daftar Lagi
                    </a>
                @else
                    <p class="text-danger">Tiket tidak ditemukan.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection