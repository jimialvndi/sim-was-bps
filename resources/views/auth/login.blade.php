@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5 col-lg-4">
            
            <div class="text-center mb-4">
                <img src="{{ asset('img/logo_bps.jpg') }}" alt="Logo BPS" width="100" class="mb-3">
                
                <h4 class="fw-bold text-dark">SIM-WAS BPS</h4>
                <p class="text-muted small">Sistem Informasi Pengawasan Lapangan</p>
            </div>

            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-4 p-md-5">
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold small text-uppercase text-secondary">Alamat Email</label>
                            <input id="email" type="email" 
                                class="form-control form-control-lg bg-light border-0 @error('email') is-invalid @enderror" 
                                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                placeholder="nama@bps.go.id">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold small text-uppercase text-secondary">Kata Sandi</label>
                            <input id="password" type="password" 
                                class="form-control form-control-lg bg-light border-0 @error('password') is-invalid @enderror" 
                                name="password" required autocomplete="current-password"
                                placeholder="********">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-muted" for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none small fw-bold" href="{{ route('password.request') }}">
                                    Lupa Password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill fw-bold shadow-sm">
                            MASUK APLIKASI
                        </button>

                    </form>
                </div>
            </div>

            <div class="text-center mt-4 text-muted small">
                &copy; {{ date('Y') }} Badan Pusat Statistik Kabupaten Bengkayang
            </div>

        </div>
    </div>
</div>

<style>
    body {
        background-color: #f8f9fa; /* Latar belakang abu-abu muda */
    }
    .form-control:focus {
        box-shadow: none;
        border: 2px solid #0d6efd; /* Highlight biru saat diklik */
        background-color: #fff;
    }
</style>
@endsection