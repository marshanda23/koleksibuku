@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-store"></i>
        </span>
        Vendor Register
    </h3>
</div>

<div class="row">
    <div class="col-md-5 mx-auto">
        <div class="card shadow-sm border-0" style="border-radius:12px">
            <div class="card-body p-4">

                <h4 class="text-primary mb-4">
                    <i class="mdi mdi-account-plus-outline me-2"></i>
                    Daftar Akun Vendor
                </h4>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('kantin.vendor.register.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Nama Vendor</label>
                        <input type="text" name="nama_vendor"
                               class="form-control border-primary @error('nama_vendor') is-invalid @enderror"
                               placeholder="Contoh: Kantin Bu Sari"
                               value="{{ old('nama_vendor') }}" required>
                        @error('nama_vendor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username"
                               class="form-control border-primary @error('username') is-invalid @enderror"
                               placeholder="Masukkan username unik"
                               value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password"
                               class="form-control border-primary @error('password') is-invalid @enderror"
                               placeholder="Minimal 6 karakter" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation"
                               class="form-control border-primary"
                               placeholder="Ulangi password" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="mdi mdi-account-check me-1"></i> Daftar
                        </button>
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('kantin.vendor.login') }}" class="text-primary">
                            Sudah punya akun? Login
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
.border-primary { border: 1px solid #b66dff !important; }
</style>

@endsection