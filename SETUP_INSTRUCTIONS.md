# Setup Instructions for Cross-Server API Integration

## Overview
This guide shows you how to integrate the Appointment API from your main server into another Laravel project on a different server.

## Prerequisites
- Your main API server is running and accessible
- Your other Laravel project has HTTP client capabilities
- Both servers can communicate over HTTP/HTTPS

## Step 1: Copy Files to Your Other Project

### 1.1 Copy the API Service Class
Copy `AppointmentApiService.php` to your other Laravel project:
```
app/Services/AppointmentApiService.php
```

### 1.2 Copy the Controller (Optional)
Copy `OtherProjectController.php` to your other Laravel project:
```
app/Http/Controllers/AppointmentController.php
```

## Step 2: Configure Environment Variables

Add these to your other project's `.env` file:
```env
# Appointment API Configuration
APPOINTMENT_API_URL=http://your-api-server.com/api
APPOINTMENT_API_TIMEOUT=30
APPOINTMENT_API_RETRY_ATTEMPTS=3
```

**Important:** Replace `http://your-api-server.com/api` with your actual API server URL.

## Step 3: Update Configuration

Add this to your other project's `config/services.php`:
```php
'appointment_api' => [
    'url' => env('APPOINTMENT_API_URL', 'http://localhost/bansalimmigration.com.au/api'),
    'timeout' => env('APPOINTMENT_API_TIMEOUT', 30),
    'retry_attempts' => env('APPOINTMENT_API_RETRY_ATTEMPTS', 3),
],
```

## Step 4: Add Routes (Optional)

If you want to use the provided controller, add these routes to your other project's `routes/web.php`:
```php
use App\Http\Controllers\AppointmentController;

Route::prefix('appointments')->name('appointments.')->group(function () {
    Route::get('/login', [AppointmentController::class, 'showLogin'])->name('login');
    Route::post('/login', [AppointmentController::class, 'login'])->name('login.post');
    Route::post('/logout', [AppointmentController::class, 'logout'])->name('logout');

    Route::middleware('auth.appointments')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [AppointmentController::class, 'create'])->name('create');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{id}', [AppointmentController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
        
        Route::get('/statistics/overview', [AppointmentController::class, 'statistics'])->name('statistics');
        Route::post('/bulk-update-status', [AppointmentController::class, 'bulkUpdateStatus'])->name('bulk-update');
        Route::get('/date-range/search', [AppointmentController::class, 'dateRange'])->name('date-range');
    });
});
```

## Step 5: Test the Integration

### 5.1 Basic Test
Create a simple test script in your other project:

```php
<?php
// test_api.php

require_once 'vendor/autoload.php';

use App\Services\AppointmentApiService;

$apiService = new AppointmentApiService();

try {
    // Test login
    $loginResponse = $apiService->login('ankitbansal0011@gmail.au', 'your_password');
    echo "✅ Login successful!\n";
    
    // Test getting appointments
    $appointments = $apiService->getAppointments(['per_page' => 5]);
    echo "✅ Found " . count($appointments['data']['data']) . " appointments\n";
    
    echo "🎉 API integration is working!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
```

### 5.2 Run the Test
```bash
php test_api.php
```

## Step 6: Common URL Configurations

### Local Development
```env
APPOINTMENT_API_URL=http://localhost/bansalimmigration.com.au/api
```

### Same Server, Different Port
```env
APPOINTMENT_API_URL=http://localhost:8080/api
```

### Different Server (HTTP)
```env
APPOINTMENT_API_URL=http://api.yourdomain.com/api
```

### Different Server (HTTPS)
```env
APPOINTMENT_API_URL=https://api.yourdomain.com/api
```

### With Custom Path
```env
APPOINTMENT_API_URL=https://yourdomain.com/laravel-project/public/api
```

## Step 7: Security Considerations

### 7.1 HTTPS
Always use HTTPS in production:
```env
APPOINTMENT_API_URL=https://your-api-server.com/api
```

### 7.2 API Credentials
Store API credentials securely:
```env
APPOINTMENT_API_EMAIL=your_email@example.com
APPOINTMENT_API_PASSWORD=your_secure_password
```

### 7.3 Token Management
The service automatically caches tokens. You can customize the cache duration in the service class.

## Step 8: Error Handling

The service includes comprehensive error handling. Common errors and solutions:

### Connection Errors
- Check if the API server is accessible
- Verify the URL in your `.env` file
- Check firewall settings

### Authentication Errors
- Verify your email and password
- Check if the admin account exists in the API server
- Ensure the API server is using the correct database

### Token Expiration
- Tokens are automatically cached for 24 hours
- The service will automatically redirect to login when tokens expire

## Step 9: Advanced Usage

### 9.1 Custom Base URL
```php
$apiService = new AppointmentApiService('http://custom-server.com/api');
```

### 9.2 Using with Pre-existing Token
```php
$apiService = new AppointmentApiService();
$apiService->setToken('your_existing_token');
```

### 9.3 Check Token Validity
```php
if ($apiService->isTokenValid()) {
    echo "Token is valid";
} else {
    echo "Token is expired";
}
```

## Step 10: Troubleshooting

### 10.1 Common Issues

**Issue:** "Connection refused"
- **Solution:** Check if the API server is running and accessible

**Issue:** "404 Not Found"
- **Solution:** Verify the API URL path is correct

**Issue:** "Authentication failed"
- **Solution:** Check email/password and ensure admin account exists

**Issue:** "Token expired"
- **Solution:** The service will automatically handle this, but you can manually logout and login again

### 10.2 Debug Mode
Enable debug mode in your other project's `.env`:
```env
APP_DEBUG=true
```

### 10.3 Log API Calls
Add logging to the service class if needed:
```php
Log::info('API Call', [
    'url' => $this->baseUrl . '/appointments',
    'method' => 'GET',
    'params' => $params
]);
```

## Step 11: Production Deployment

### 11.1 Environment Variables
Ensure all environment variables are set in production:
```env
APPOINTMENT_API_URL=https://your-production-api.com/api
APPOINTMENT_API_TIMEOUT=30
APPOINTMENT_API_RETRY_ATTEMPTS=3
```

### 11.2 SSL Certificates
Ensure both servers have valid SSL certificates for HTTPS communication.

### 11.3 Firewall Rules
Configure firewall rules to allow communication between servers.

## Support

If you encounter issues:
1. Check the error messages in your Laravel logs
2. Verify the API server is accessible
3. Test the API endpoints directly with Postman
4. Check the network connectivity between servers

## Files Summary

- `AppointmentApiService.php` - Main service class for API integration
- `OtherProjectController.php` - Example controller for web interface
- `routes_other_project.php` - Example routes
- `config_other_project.php` - Configuration settings
- `env_other_project.txt` - Environment variables
- `usage_examples.php` - Usage examples
- `SETUP_INSTRUCTIONS.md` - This setup guide 