# External Laravel Integration Guide - No Login Required

## 🎯 Overview

This guide shows you how to integrate the appointment API from `bansalimmigration.com.au` into your other Laravel website `migrationmanager.bansalcrm.com` **without requiring login each time**. We'll use a **Service Account** approach with permanent tokens.

## 🚀 Quick Start (3 Steps)

### Step 1: Generate Service Token (One-time setup)

First, generate a permanent service token using your admin credentials:

```bash
curl -X POST https://www.bansalimmigration.com.au/api/service-account/generate-token \
  -H "Content-Type: application/json" \
  -d '{
    "service_name": "Migration Manager CRM",
    "description": "Integration for migrationmanager.bansalcrm.com",
    "admin_email": "admin1@gmail.com",
    "admin_password": "123456"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Service account created successfully. Store this token securely.",
  "data": {
    "service_account": {
      "id": 1,
      "service_name": "My Other Website",
      "token": "abc123def456ghi789...",
      "created_at": "2024-01-15T10:00:00.000000Z"
    }
  }
}
```

**⚠️ Save this token securely - you'll use it in your other website!**

### Step 2: Add to Your Other Laravel Project

#### 2.1 Create Service Class
Create `app/Services/AppointmentApiService.php` in your other Laravel project:

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class AppointmentApiService
{
    protected $baseUrl;
    protected $serviceToken;
    protected $token;
    protected $tokenExpiry;

    public function __construct($baseUrl = null, $serviceToken = null)
    {
        $this->baseUrl = $baseUrl ?? config('services.appointment_api.url', 'https://bansalimmigration.com.au/api');
        $this->serviceToken = $serviceToken ?? config('services.appointment_api.service_token');
    }

    /**
     * Authenticate using service token (no login required)
     */
    public function authenticate()
    {
        try {
            // Check if we have a cached token
            $cachedToken = Cache::get('appointment_api_token');
            if ($cachedToken) {
                $this->token = $cachedToken;
                return true;
            }

            // Authenticate with service token
            $response = Http::timeout(30)->post($this->baseUrl . '/service-account/authenticate', [
                'service_token' => $this->serviceToken
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    $this->token = $data['data']['token'];
                    
                    // Cache the token for 24 hours
                    Cache::put('appointment_api_token', $this->token, now()->addHours(24));
                    
                    return true;
                } else {
                    throw new Exception($data['message'] ?? 'Service authentication failed');
                }
            }

            throw new Exception('Service authentication failed: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Authentication error: ' . $e->getMessage());
        }
    }

    /**
     * Get all appointments with optional filters
     */
    public function getAppointments($params = [])
    {
        $this->ensureAuthenticated();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->get($this->baseUrl . '/appointments', $params);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to fetch appointments: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Get appointments error: ' . $e->getMessage());
        }
    }

    /**
     * Get appointment statistics
     */
    public function getStatistics()
    {
        $this->ensureAuthenticated();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->get($this->baseUrl . '/appointments/statistics/overview');

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to fetch statistics: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Get statistics error: ' . $e->getMessage());
        }
    }

    /**
     * Create new appointment
     */
    public function createAppointment($data)
    {
        $this->ensureAuthenticated();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->post($this->baseUrl . '/appointments', $data);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to create appointment: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Create appointment error: ' . $e->getMessage());
        }
    }

    /**
     * Update appointment
     */
    public function updateAppointment($id, $data)
    {
        $this->ensureAuthenticated();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->put($this->baseUrl . '/appointments/' . $id, $data);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to update appointment: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Update appointment error: ' . $e->getMessage());
        }
    }

    /**
     * Delete appointment
     */
    public function deleteAppointment($id)
    {
        $this->ensureAuthenticated();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->delete($this->baseUrl . '/appointments/' . $id);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to delete appointment: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Delete appointment error: ' . $e->getMessage());
        }
    }

    /**
     * Ensure we are authenticated
     */
    protected function ensureAuthenticated()
    {
        if (!$this->token) {
            $this->authenticate();
        }
    }

    /**
     * Clear cached token (useful for testing)
     */
    public function clearCache()
    {
        Cache::forget('appointment_api_token');
        $this->token = null;
    }
}
```

#### 2.2 Add Configuration
Add to your other Laravel project's `config/services.php`:

```php
'appointment_api' => [
    'url' => env('APPOINTMENT_API_URL', 'https://migrationmanager.bansalcrm.com/api'),
    'service_token' => env('APPOINTMENT_API_SERVICE_TOKEN'),
    'timeout' => env('APPOINTMENT_API_TIMEOUT', 30),
],
```

Add to your `.env` file:

```env
# Appointment API Configuration
APPOINTMENT_API_URL=https://bansalimmigration.com.au/api
APPOINTMENT_API_SERVICE_TOKEN=your_service_token_from_step_1
APPOINTMENT_API_TIMEOUT=30
```

### Step 3: Use in Your Controller

Create a controller in your other Laravel project:

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
        $this->apiService = new AppointmentApiService();
    }

    /**
     * Display appointments list
     */
    public function index(Request $request)
    {
        try {
            // Get search parameters
            $filters = [];
            if ($request->search) $filters['search'] = $request->search;
            if ($request->status) $filters['status'] = $request->status;
            if ($request->date_from) $filters['date_from'] = $request->date_from;
            if ($request->date_to) $filters['date_to'] = $request->date_to;
            if ($request->per_page) $filters['per_page'] = $request->per_page;

            // Get appointments
            $appointments = $this->apiService->getAppointments($filters);
            
            // Get statistics
            $statistics = $this->apiService->getStatistics();

            return view('appointments.index', compact('appointments', 'statistics'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to fetch appointments: ' . $e->getMessage());
        }
    }

    /**
     * Create new appointment
     */
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'date' => 'required|date',
            'time' => 'required',
            'description' => 'required|string',
            'status' => 'required|in:pending,confirmed,completed,cancelled,rescheduled'
        ]);

        try {
            $appointment = $this->apiService->createAppointment($request->all());
            return redirect()->route('appointments.index')->with('success', 'Appointment created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create appointment: ' . $e->getMessage());
        }
    }

    /**
     * Update appointment
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'date' => 'required|date',
            'time' => 'required',
            'description' => 'required|string',
            'status' => 'required|in:pending,confirmed,completed,cancelled,rescheduled'
        ]);

        try {
            $appointment = $this->apiService->updateAppointment($id, $request->all());
            return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update appointment: ' . $e->getMessage());
        }
    }

    /**
     * Delete appointment
     */
    public function destroy($id)
    {
        try {
            $this->apiService->deleteAppointment($id);
            return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete appointment: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint for AJAX requests
     */
    public function apiIndex(Request $request)
    {
        try {
            $filters = $request->all();
            $appointments = $this->apiService->getAppointments($filters);
            return response()->json($appointments);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
```

## 📋 Complete Example Usage

### 1. Simple Appointments List

```php
// In your controller
public function dashboard()
{
    try {
        $apiService = new AppointmentApiService();
        
        // Get recent appointments
        $appointments = $apiService->getAppointments([
            'per_page' => 5,
            'sort_by' => 'created_at',
            'sort_order' => 'desc'
        ]);

        // Get statistics
        $statistics = $apiService->getStatistics();

        return view('dashboard', compact('appointments', 'statistics'));
    } catch (\Exception $e) {
        // Handle error
        return view('dashboard')->with('error', $e->getMessage());
    }
}
```

### 2. Search and Filter

```php
public function search(Request $request)
{
    try {
        $apiService = new AppointmentApiService();
        
        $filters = [];
        if ($request->search) {
            $filters['search'] = $request->search;
        }
        if ($request->status) {
            $filters['status'] = $request->status;
        }
        if ($request->date_from) {
            $filters['date_from'] = $request->date_from;
        }
        if ($request->date_to) {
            $filters['date_to'] = $request->date_to;
        }

        $appointments = $apiService->getAppointments($filters);
        
        return response()->json($appointments);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
```

### 3. Create Appointment from Form

```php
public function createAppointment(Request $request)
{
    $request->validate([
        'full_name' => 'required',
        'email' => 'required|email',
        'date' => 'required|date',
        'time' => 'required',
        'description' => 'required'
    ]);

    try {
        $apiService = new AppointmentApiService();
        
        $appointmentData = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
            'status' => 'pending',
            'appointment_details' => $request->appointment_details ?? ''
        ];

        $result = $apiService->createAppointment($appointmentData);
        
        return response()->json([
            'success' => true,
            'message' => 'Appointment created successfully',
            'data' => $result['data']
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
```

## 🔧 Advanced Features

### 1. Caching Strategy

The service automatically caches the authentication token for 24 hours. You can customize this:

```php
// In AppointmentApiService.php
Cache::put('appointment_api_token', $this->token, now()->addHours(48)); // 48 hours
```

### 2. Error Handling

```php
try {
    $apiService = new AppointmentApiService();
    $appointments = $apiService->getAppointments();
} catch (\Exception $e) {
    // Log error
    \Log::error('Appointment API Error: ' . $e->getMessage());
    
    // Show user-friendly message
    $errorMessage = 'Unable to load appointments at this time. Please try again later.';
}
```

### 3. Queue Integration

For heavy operations, use Laravel queues:

```php
// Create a job
php artisan make:job SyncAppointments

// In the job
public function handle()
{
    $apiService = new AppointmentApiService();
    $appointments = $apiService->getAppointments();
    
    // Process appointments in background
    foreach ($appointments['data']['data'] as $appointment) {
        // Sync to local database or process
    }
}

// Dispatch the job
SyncAppointments::dispatch();
```

## 🛡️ Security Best Practices

### 1. Environment Variables
Always store the service token in `.env`:
```env
APPOINTMENT_API_SERVICE_TOKEN=your_secure_token_here
```

### 2. Token Rotation
Regularly rotate your service tokens:
```bash
# Generate new token
curl -X POST https://bansalimmigration.com.au/api/service-account/generate-token \
  -H "Content-Type: application/json" \
  -d '{
    "service_name": "Migration Manager CRM",
    "description": "Updated integration token for migrationmanager.bansalcrm.com",
    "admin_email": "admin1@gmail.com",
    "admin_password": "123456"
  }'
```

### 3. HTTPS Only
Always use HTTPS in production:
```env
APPOINTMENT_API_URL=https://bansalimmigration.com.au/api
```

## 🎯 Available API Endpoints

With your service token, you can access all these endpoints:

- `GET /api/appointments` - Get all appointments
- `POST /api/appointments` - Create appointment
- `GET /api/appointments/{id}` - Get single appointment
- `PUT /api/appointments/{id}` - Update appointment
- `DELETE /api/appointments/{id}` - Delete appointment
- `GET /api/appointments/statistics/overview` - Get statistics
- `POST /api/appointments/bulk-update-status` - Bulk update
- `GET /api/appointments/date-range/search` - Date range search

## 🚀 Benefits of This Approach

✅ **No Login Required** - Use permanent service token
✅ **Automatic Authentication** - Handles token refresh automatically
✅ **Caching** - Reduces API calls with smart caching
✅ **Error Handling** - Comprehensive error handling
✅ **Security** - Secure token-based authentication
✅ **Scalable** - Works with multiple external applications
✅ **Maintainable** - Clean, reusable service class

## 🎉 You're Ready!

Now you can use the appointment API in your other Laravel website without any login process. The service account approach provides secure, permanent access to all appointment functionality.

**Next Steps:**
1. Generate your service token using the curl command above
2. Add the service class to your other Laravel project
3. Configure your `.env` file
4. Start using the API in your controllers!

Need help? Check the troubleshooting section below or review the API documentation. 