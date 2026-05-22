@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="mdi mdi-ticket-account"></i> Daftar Antrian</h4>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif
                <form action="{{ route('antrian.daftar') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control form-control-lg"
                               placeholder="Masukkan nama kamu..." required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="mdi mdi-check-circle"></i> Ambil Nomor Antrian
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection