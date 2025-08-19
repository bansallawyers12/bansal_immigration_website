@extends('layouts.app')

@section('title', 'Appointment Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Appointment System Login
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('appointments.login.post') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-1"></i>
                                Email Address
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-1"></i>
                                Password
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Login to Appointment System
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Available Admin Accounts:
                        </small>
                        <div class="mt-2">
                            <small class="text-muted">
                                • ankitbansal0011@gmail.au<br>
                                • arunbnsal@gmail.com<br>
                                • amandhillon79172@gmail.com<br>
                                • skirandeep693@gmail.com
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Info -->
            <div class="card mt-3 border-0 bg-light">
                <div class="card-body text-center">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Secure API Integration
                    </small>
                    <br>
                    <small class="text-muted">
                        Connected to: {{ config('services.appointment_api.url') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.btn-primary {
    border-radius: 10px;
    padding: 12px 24px;
}

.form-control {
    border-radius: 8px;
    padding: 12px 16px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}
</style>
@endsection 