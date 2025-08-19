@extends('layouts.admin')

@section('title', 'Generate Service Token')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-key me-2"></i>
                        Generate Service Token for External API Access
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            <h5>✅ Service Token Generated Successfully!</h5>
                            <p><strong>Service Name:</strong> {{ session('service_name') }}</p>
                            <p><strong>Token:</strong> <code>{{ session('token') }}</code></p>
                            <p><strong>Created:</strong> {{ session('created_at') }}</p>
                            <hr>
                            <p class="mb-0"><strong>⚠️ IMPORTANT:</strong> Save this token securely in your other Laravel project's .env file:</p>
                            <code>APPOINTMENT_API_SERVICE_TOKEN={{ session('token') }}</code>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            <h5>❌ Error Generating Token</h5>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.generate.token') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_email" class="form-label">Admin Email *</label>
                                    <input type="email" class="form-control @error('admin_email') is-invalid @enderror" 
                                           id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
                                    @error('admin_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admin_password" class="form-label">Admin Password *</label>
                                    <input type="password" class="form-control @error('admin_password') is-invalid @enderror" 
                                           id="admin_password" name="admin_password" required>
                                    @error('admin_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_name" class="form-label">Service Name *</label>
                                    <input type="text" class="form-control @error('service_name') is-invalid @enderror" 
                                           id="service_name" name="service_name" value="{{ old('service_name', 'My Other Website') }}" required>
                                    @error('service_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="2">{{ old('description', 'Integration for external Laravel website') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Instructions:</h6>
                            <ul class="mb-0">
                                <li>Use the admin credentials that have access to the appointment system</li>
                                <li>Service name should be descriptive (e.g., "Company Website Integration")</li>
                                <li>Description helps identify the purpose of this token</li>
                                <li>Generated token will be valid for external API access</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key me-2"></i>Generate Service Token
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 