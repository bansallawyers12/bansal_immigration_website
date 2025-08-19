# Laravel-to-Laravel API Integration Guide

## 🚀 Complete Setup for Laravel Projects

This guide shows you how to integrate your appointment APIs from one Laravel project into another Laravel project on a different server.

## 📋 Prerequisites

- Your API server (Laravel) is running and accessible
- Your other Laravel project has HTTP client capabilities
- Both servers can communicate over HTTP/HTTPS
- Laravel 8+ on both projects

## 🔧 Step-by-Step Setup

### Step 1: Copy Files to Your Other Laravel Project

#### 1.1 Copy the Service Class
Copy `AppointmentApiService.php` to your other Laravel project:
```
app/Services/AppointmentApiService.php
```

#### 1.2 Copy the Controller
Copy `AppointmentController_Laravel.php` to your other Laravel project:
```
app/Http/Controllers/AppointmentController.php
```

#### 1.3 Copy the Views
Create the views directory and copy the Blade files:
```
resources/views/appointments/
├── index.blade.php (copy from appointments_index.blade.php)
├── login.blade.php (copy from appointments_login.blade.php)
├── create.blade.php
├── edit.blade.php
├── show.blade.php
└── dashboard-widget.blade.php
```

### Step 2: Add Configuration

#### 2.1 Update config/services.php
Add this to your other Laravel project's `config/services.php`:

```php
'appointment_api' => [
    'url' => env('APPOINTMENT_API_URL', 'http://localhost/bansalimmigration.com.au/api'),
    'timeout' => env('APPOINTMENT_API_TIMEOUT', 30),
    'retry_attempts' => env('APPOINTMENT_API_RETRY_ATTEMPTS', 3),
],
```

#### 2.2 Update .env file
Add these to your other Laravel project's `.env` file:

```env
# Appointment API Configuration
APPOINTMENT_API_URL=http://your-api-server.com/api
APPOINTMENT_API_TIMEOUT=30
APPOINTMENT_API_RETRY_ATTEMPTS=3
```

### Step 3: Add Routes

Add these routes to your other Laravel project's `routes/web.php`:

```php
use App\Http\Controllers\AppointmentController;

// Appointment routes
Route::prefix('appointments')->name('appointments.')->group(function () {
    // Authentication routes
    Route::get('/login', [AppointmentController::class, 'showLogin'])->name('login');
    Route::post('/login', [AppointmentController::class, 'login'])->name('login.post');
    Route::post('/logout', [AppointmentController::class, 'logout'])->name('logout');

    // Protected routes (require authentication)
    Route::middleware('auth.appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [AppointmentController::class, 'create'])->name('create');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
        
        // Additional routes
        Route::get('/statistics/overview', [AppointmentController::class, 'statistics'])->name('statistics');
        Route::post('/bulk-update-status', [AppointmentController::class, 'bulkUpdateStatus'])->name('bulk-update');
        Route::get('/date-range/search', [AppointmentController::class, 'dateRange'])->name('date-range');
        
        // API endpoints for AJAX
        Route::get('/api/list', [AppointmentController::class, 'apiIndex'])->name('api.list');
        
        // Dashboard widget
        Route::get('/dashboard/widget', [AppointmentController::class, 'dashboardWidget'])->name('dashboard.widget');
    });
});
```

### Step 4: Create Middleware (Optional)

#### 4.1 Create Middleware
Create `app/Http/Middleware/CheckAppointmentApiAuth.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CheckAppointmentApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = Cache::get('appointment_api_token');
        
        if (!$token) {
            return redirect()->route('appointments.login')
                ->with('error', 'Please login to access appointments');
        }

        return $next($request);
    }
}
```

#### 4.2 Register Middleware
Add this to your other Laravel project's `app/Http/Kernel.php`:

```php
protected $routeMiddleware = [
    // ... other middlewares
    'auth.appointments' => \App\Http\Middleware\CheckAppointmentApiAuth::class,
];
```

### Step 5: Create Additional Views

#### 5.1 Create Form (create.blade.php)
```php
@extends('layouts.app')

@section('title', 'Create Appointment')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Create New Appointment</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('appointments.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                           id="date" name="date" value="{{ old('date') }}" required>
                                    @error('date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="time" class="form-label">Time</label>
                                    <input type="time" class="form-control @error('time') is-invalid @enderror" 
                                           id="time" name="time" value="{{ old('time') }}" required>
                                    @error('time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="rescheduled" {{ old('status') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="appointment_details" class="form-label">Appointment Details</label>
                            <textarea class="form-control @error('appointment_details') is-invalid @enderror" 
                                      id="appointment_details" name="appointment_details" rows="4" required>{{ old('appointment_details') }}</textarea>
                            @error('appointment_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('appointments.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```

#### 5.2 Dashboard Widget (dashboard-widget.blade.php)
```php
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-calendar-alt me-2"></i>
            Recent Appointments
        </h5>
    </div>
    <div class="card-body">
        @if(isset($recentAppointments['data']['data']) && count($recentAppointments['data']['data']) > 0)
            <div class="list-group list-group-flush">
                @foreach($recentAppointments['data']['data'] as $appointment)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ $appointment['full_name'] }}</h6>
                            <small class="text-muted">{{ $appointment['date'] }} {{ $appointment['time'] }}</small>
                        </div>
                        <span class="badge bg-{{ getStatusColor($appointment['status']) }} rounded-pill">
                            {{ ucfirst($appointment['status']) }}
                        </span>
                    </div>
                @endforeach
            </div>
            <div class="mt-3">
                <a href="{{ route('appointments.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
        @else
            <p class="text-muted mb-0">No recent appointments</p>
        @endif
    </div>
</div>

@php
function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending': return 'warning';
        case 'confirmed': return 'success';
        case 'completed': return 'info';
        case 'cancelled': return 'danger';
        case 'rescheduled': return 'secondary';
        default: return 'primary';
    }
}
@endphp
```

### Step 6: Test the Integration

#### 6.1 Test Login
Visit: `http://your-other-laravel-project.com/appointments/login`

#### 6.2 Test Appointments List
After login, visit: `http://your-other-laravel-project.com/appointments`

## 🎯 Common URL Configurations

### Local Development
```env
APPOINTMENT_API_URL=http://localhost/bansalimmigration.com.au/api
```

### Different Server (HTTP)
```env
APPOINTMENT_API_URL=http://api.yourdomain.com/api
```

### Different Server (HTTPS)
```env
APPOINTMENT_API_URL=https://api.yourdomain.com/api
```

### Custom Path
```env
APPOINTMENT_API_URL=https://yourdomain.com/laravel-project/public/api
```

## 🔒 Security Considerations

### 1. HTTPS in Production
Always use HTTPS in production:
```env
APPOINTMENT_API_URL=https://your-api-server.com/api
```

### 2. Environment Variables
Store credentials securely in `.env`:
```env
APPOINTMENT_API_URL=https://your-api-server.com/api
APPOINTMENT_API_TIMEOUT=30
APPOINTMENT_API_RETRY_ATTEMPTS=3
```

### 3. CORS Configuration
If needed, add CORS headers to your API server's `app/Http/Middleware/Cors.php`:

```php
public function handle($request, Closure $next)
{
    return $next($request)
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
}
```

## 📱 Mobile Responsive

The views include Bootstrap 5 for mobile responsiveness. Ensure your layout includes:

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
```

## 🔄 Auto-Refresh

The integration includes automatic refresh every 5 minutes. You can customize this in the views:

```javascript
// Auto-refresh every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);
```

## 🎨 Customization

### 1. Custom Styling
Add your own CSS to match your Laravel project's theme:

```css
/* Custom appointment styling */
.appointment-card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.appointment-table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
```

### 2. Custom Layout
Extend your existing layout in the views:

```php
@extends('layouts.admin') // Your custom layout
```

## 🛠️ Troubleshooting

### Common Issues:

**1. Connection Failed**
- Check if API server is accessible
- Verify URL in `.env` file
- Check firewall settings

**2. Authentication Failed**
- Verify email and password
- Check if admin account exists
- Ensure API server is using correct database

**3. Route Not Found**
- Check if routes are properly registered
- Clear route cache: `php artisan route:clear`
- Check route list: `php artisan route:list`

**4. View Not Found**
- Ensure Blade files are in correct directory
- Clear view cache: `php artisan view:clear`

### Debug Commands:
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check routes
php artisan route:list --name=appointments

# Check configuration
php artisan config:show services.appointment_api
```

## 📈 Performance Optimization

### 1. Caching
The service automatically caches tokens. You can customize cache duration:

```php
// In AppointmentApiService.php
Cache::put('appointment_api_token', $this->token, now()->addHours(24));
```

### 2. Queue Jobs
For heavy operations, use Laravel queues:

```php
// Create a job
php artisan make:job SyncAppointments

// In the job
public function handle()
{
    $apiService = new AppointmentApiService();
    $appointments = $apiService->getAppointments();
    // Process appointments
}
```

## 🎉 Success!

Once integrated, you'll have:
- ✅ Full CRUD operations for appointments
- ✅ Search and filtering capabilities
- ✅ Mobile responsive design
- ✅ Secure API communication
- ✅ Statistics and overview
- ✅ Professional Laravel integration
- ✅ Token-based authentication
- ✅ Auto-refresh functionality

Your Laravel project will now seamlessly display and manage appointments from your API server! 