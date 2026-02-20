@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Verifikasi Kode OTP</div>

                <div class="card-body text-center">
                    <p>Silakan masukkan 6 digit kode unik yang telah dikirimkan ke email Anda.</p>
                    
                    <form method="POST" action="{{ route('otp.verify') }}">
                        @csrf

                        <div class="mb-3">
                            <input type="text" name="otp" 
                                   class="form-control form-control-lg text-center @error('otp') is-invalid @enderror" 
                                   maxlength="6" placeholder="000000" required autofocus
                                   style="letter-spacing: 10px; font-weight: bold; font-size: 2rem;">

                            @error('otp')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Verifikasi Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection