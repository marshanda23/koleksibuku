@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-store"></i>
        </span>
        Vendor Login
    </h3>
</div>

<div class="row">
    <div class="col-md-5 mx-auto">
        <div class="card shadow-sm border-0" style="border-radius:12px">
            <div class="card-body p-4">

                <h4 class="text-primary mb-4">
                    <i class="mdi mdi-lock-outline me-2"></i>
                    Login Vendor Kantin
                </h4>

                @if($errors->has('login'))
                    <div class="alert alert-danger">
                        {{ $errors->first('login') }}
                    </div>
                @endif

                <form action="{{ route('kantin.vendor.login.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text"
                               name="username"
                               class="form-control border-primary"
                               placeholder="Masukkan username"
                               value="{{ old('username') }}"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password"
                               name="password"
                               class="form-control border-primary"
                               placeholder="Masukkan password"
                               required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-gradient-primary">
                            <i class="mdi mdi-login me-1"></i> Login
                        </button>
                    </div>
                    <div class="text-center mt-3">
    <a href="{{ route('kantin.vendor.forgot') }}" class="text-muted" style="font-size:13px">
        Lupa password?
    </a>
</div>
<div class="text-center mt-2">
    <a href="{{ route('kantin.vendor.register') }}" class="text-primary" style="font-size:13px">
        Belum punya akun? Daftar
    </a>
</div>
                </form>

            </div>
        </div>
    </div>
</div>

<style>
.border-primary {
    border: 1px solid #b66dff !important;
}
</style>

@endsection