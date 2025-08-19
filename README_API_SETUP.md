# Appointment Management API Setup Guide

## Overview
This guide will help you set up and use the Appointment Management API with Laravel Sanctum authentication.

## Prerequisites
- PHP 8.0 or higher
- Laravel 8.x or higher
- MySQL/MariaDB database
- Composer

## Installation Steps

### 1. Install Laravel Sanctum
```bash
composer require laravel/sanctum
```

### 2. Publish Sanctum Configuration
```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Configure Sanctum in config/sanctum.php
Make sure the following configuration is set:
```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
))),
```

### 5. Update User Model
Ensure your User model uses the `HasApiTokens` trait:
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;
    // ... rest of your model
}
```

## API Endpoints

### Authentication
- **POST** `/api/login` - Login and get token
- **POST** `/api/logout` - Logout and revoke token

### Appointments
- **GET** `/api/appointments` - Get all appointments (with pagination, search, filters)
- **POST** `/api/appointments` - Create new appointment
- **GET** `/api/appointments/{id}` - Get single appointment
- **PUT/PATCH** `/api/appointments/{id}` - Update appointment
- **DELETE** `/api/appointments/{id}` - Delete appointment
- **GET** `/api/appointments/statistics/overview` - Get appointment statistics
- **POST** `/api/appointments/bulk-update-status` - Bulk update appointment status
- **GET** `/api/appointments/date-range/search` - Get appointments by date range

## Usage Examples

### 1. Login and Get Token
```bash
curl -X POST http://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

### 2. Get All Appointments
```bash
curl -X GET http://your-domain.com/api/appointments \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### 3. Create Appointment
```bash
curl -X POST http://your-domain.com/api/appointments \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Doe",
    "email": "john@example.com",
    "date": "2024-01-15",
    "time": "10:00",
    "description": "Consultation meeting",
    "status": "pending",
    "appointment_details": "Detailed notes about the appointment"
  }'
```

### 4. Update Appointment
```bash
curl -X PUT http://your-domain.com/api/appointments/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "confirmed",
    "description": "Updated consultation meeting"
  }'
```

### 5. Delete Appointment
```bash
curl -X DELETE http://your-domain.com/api/appointments/1 \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Testing

### Run Tests
```bash
php artisan test --filter=AppointmentApiTest
```

### Run Specific Test
```bash
php artisan test --filter=AppointmentApiTest::it_can_create_an_appointment
```

## Using in Other Laravel Projects

### 1. Create API Service Class
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AppointmentApiService
{
    protected $baseUrl;
    protected $token;

    public function __construct($baseUrl, $token = null)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }

    public function login($email, $password, $clientId)
    {
        $response = Http::post($this->baseUrl . '/login', [
            'email' => $email,
            'password' => $password,
            'client_id' => $clientId
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $this->token = $data['data']['token'];
            return $data;
        }

        throw new \Exception($response->json()['message']);
    }

    public function getAppointments($params = [])
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . '/appointments', $params)
            ->json();
    }

    public function createAppointment($data)
    {
        return Http::withToken($this->token)
            ->post($this->baseUrl . '/appointments', $data)
            ->json();
    }

    public function updateAppointment($id, $data)
    {
        return Http::withToken($this->token)
            ->put($this->baseUrl . '/appointments/' . $id, $data)
            ->json();
    }

    public function deleteAppointment($id)
    {
        return Http::withToken($this->token)
            ->delete($this->baseUrl . '/appointments/' . $id)
            ->json();
    }

    public function getStatistics()
    {
        return Http::withToken($this->token)
            ->get($this->baseUrl . '/appointments/statistics/overview')
            ->json();
    }
}
```

### 2. Usage in Controller
```php
<?php

namespace App\Http\Controllers;

use App\Services\AppointmentApiService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $apiService;

    public function __construct()
    {
        $this->apiService = new AppointmentApiService(
            config('services.appointment_api.url'),
            session('api_token')
        );
    }

    public function index(Request $request)
    {
        try {
            $appointments = $this->apiService->getAppointments([
                'status' => $request->status,
                'per_page' => 15
            ]);

            return view('appointments.index', compact('appointments'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch appointments: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $appointment = $this->apiService->createAppointment($request->validated());
            return redirect()->route('appointments.index')->with('success', 'Appointment created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create appointment: ' . $e->getMessage());
        }
    }
}
```

## Configuration

### Environment Variables
Add these to your `.env` file:
```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,127.0.0.1:8000
SESSION_DOMAIN=localhost
```

### CORS Configuration
If you're calling the API from a different domain, update `config/cors.php`:
```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => ['*'],
'allowed_origins_patterns' => [],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => false,
```

## Security Considerations

1. **Token Management**: Always store tokens securely and implement proper token refresh mechanisms
2. **Rate Limiting**: Consider implementing rate limiting for API endpoints
3. **Validation**: All input is validated using Laravel's validation system
4. **CORS**: Configure CORS properly for cross-origin requests
5. **HTTPS**: Use HTTPS in production for secure communication

## Error Handling

The API returns consistent error responses:
```json
{
    "success": false,
    "message": "Error message",
    "data": {
        "field": ["Validation error message"]
    }
}
```

## Support

For issues or questions:
1. Check the API documentation in `API_DOCUMENTATION.md`
2. Review the test files for usage examples
3. Check Laravel Sanctum documentation for authentication issues

## Changelog

### Version 1.0.0
- Initial release with full CRUD operations
- Laravel Sanctum authentication
- Comprehensive validation
- Search and filtering capabilities
- Bulk operations support
- Statistics endpoint
- Full test coverage 