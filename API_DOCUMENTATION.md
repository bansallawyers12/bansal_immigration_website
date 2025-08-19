# Appointment Management API Documentation

## Overview
This API provides comprehensive CRUD operations for managing appointments with Laravel Sanctum authentication.

## Base URL
```
http://your-domain.com/api
```

## Authentication
All protected endpoints require a Bearer token obtained from the login endpoint.

### Login
**POST** `/api/login`

**Request Body:**
```json
{
    "email": "user@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Now you have login successfully.",
    "data": {
        "user_data": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com"
        },
        "token": "1|abc123...",
        "token_type": "Bearer"
    }
}
```

### Logout
**POST** `/api/logout`

**Headers:**
```
Authorization: Bearer {token}
```

## Appointment Endpoints

### 1. Get All Appointments
**GET** `/api/appointments`

**Headers:**
```
Authorization: Bearer {token}
```

**Query Parameters:**
- `search` (optional): Search in description, client_unique_id, full_name, email
- `status` (optional): Filter by status (pending, confirmed, cancelled, completed, rescheduled)
- `date_from` (optional): Filter appointments from this date
- `date_to` (optional): Filter appointments to this date
- `client_id` (optional): Filter by client ID
- `service_id` (optional): Filter by service ID
- `sort_by` (optional): Sort field (default: created_at)
- `sort_order` (optional): Sort order (asc/desc, default: desc)
- `per_page` (optional): Items per page (default: 15)

**Response:**
```json
{
    "success": true,
    "message": "Appointments retrieved successfully.",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "user_id": 1,
                "client_id": 1,
                "client_unique_id": "CLI001",
                "full_name": "John Doe",
                "email": "john@example.com",
                "date": "2024-01-15",
                "time": "10:00",
                "description": "Consultation meeting",
                "status": "confirmed",
                "appointment_details": "Detailed notes about the appointment",
                "created_at": "2024-01-10T10:00:00.000000Z",
                "updated_at": "2024-01-10T10:00:00.000000Z",
                "client": {
                    "id": 1,
                    "name": "John Doe",
                    "email": "john@example.com"
                },
                "service": {
                    "id": 1,
                    "title": "Immigration Consultation"
                }
            }
        ],
        "total": 50,
        "per_page": 15
    }
}
```

### 2. Create Appointment
**POST** `/api/appointments`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "user_id": 1,
    "client_id": 1,
    "client_unique_id": "CLI001",
    "timezone": "UTC",
    "email": "client@example.com",
    "noe_id": 1,
    "service_id": 1,
    "assinee": 2,
    "full_name": "John Doe",
    "date": "2024-01-15",
    "time": "10:00",
    "title": "Consultation",
    "description": "Initial consultation meeting",
    "status": "pending",
    "preferred_language": "English",
    "inperson_address": "123 Main St, City",
    "appointment_details": "Detailed notes about the appointment"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Appointment created successfully.",
    "data": {
        "id": 1,
        "full_name": "John Doe",
        "email": "client@example.com",
        "date": "2024-01-15",
        "time": "10:00",
        "status": "pending"
    }
}
```

### 3. Get Single Appointment
**GET** `/api/appointments/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Appointment retrieved successfully.",
    "data": {
        "id": 1,
        "user_id": 1,
        "client_id": 1,
        "full_name": "John Doe",
        "email": "client@example.com",
        "date": "2024-01-15",
        "time": "10:00",
        "status": "confirmed",
        "description": "Initial consultation meeting",
        "appointment_details": "Detailed notes about the appointment",
        "client": {
            "id": 1,
            "name": "John Doe",
            "email": "client@example.com"
        },
        "service": {
            "id": 1,
            "title": "Immigration Consultation"
        }
    }
}
```

### 4. Update Appointment
**PUT/PATCH** `/api/appointments/{id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "status": "confirmed",
    "description": "Updated consultation meeting",
    "appointment_details": "Updated detailed notes"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Appointment updated successfully.",
    "data": {
        "id": 1,
        "status": "confirmed",
        "description": "Updated consultation meeting"
    }
}
```

### 5. Delete Appointment
**DELETE** `/api/appointments/{id}`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Appointment deleted successfully.",
    "data": []
}
```

### 6. Get Statistics
**GET** `/api/appointments/statistics/overview`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Appointment statistics retrieved successfully.",
    "data": {
        "total": 150,
        "pending": 25,
        "confirmed": 80,
        "cancelled": 15,
        "completed": 25,
        "rescheduled": 5,
        "today": 8,
        "this_week": 35,
        "this_month": 120
    }
}
```

### 7. Bulk Update Status
**POST** `/api/appointments/bulk-update-status`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "appointment_ids": [1, 2, 3, 4, 5],
    "status": "confirmed"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Appointments status updated successfully.",
    "data": {
        "updated_count": 5
    }
}
```

### 8. Get Appointments by Date Range
**GET** `/api/appointments/date-range/search?start_date=2024-01-01&end_date=2024-01-31`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Appointments retrieved successfully.",
    "data": [
        {
            "id": 1,
            "full_name": "John Doe",
            "date": "2024-01-15",
            "time": "10:00",
            "status": "confirmed"
        }
    ]
}
```

## Error Responses

### Validation Error
```json
{
    "success": false,
    "message": "Validation Error.",
    "data": {
        "email": ["The email field is required."],
        "date": ["The date field is required."]
    }
}
```

### Authentication Error
```json
{
    "success": false,
    "message": "Unauthenticated."
}
```

### Not Found Error
```json
{
    "success": false,
    "message": "Appointment not found."
}
```

## Status Codes
- `200`: Success
- `201`: Created
- `400`: Bad Request
- `401`: Unauthorized
- `404`: Not Found
- `422`: Validation Error
- `500`: Server Error

## Usage in Other Laravel Projects

### 1. Install Laravel HTTP Client
```bash
composer require guzzlehttp/guzzle
```

### 2. Create API Service Class
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

    public function login($email, $password)
    {
        $response = Http::post($this->baseUrl . '/login', [
            'email' => $email,
            'password' => $password
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

### 3. Usage Example
```php
<?php

use App\Services\AppointmentApiService;

// Initialize the service
$apiService = new AppointmentApiService('http://your-api-domain.com/api');

// Login
$loginResponse = $apiService->login('user@example.com', 'password');

// Get appointments
$appointments = $apiService->getAppointments([
    'status' => 'confirmed',
    'per_page' => 10
]);

// Create appointment
$newAppointment = $apiService->createAppointment([
    'full_name' => 'John Doe',
    'email' => 'john@example.com',
    'date' => '2024-01-15',
    'time' => '10:00',
    'description' => 'Consultation meeting',
    'status' => 'pending',
    'appointment_details' => 'Detailed notes'
]);

// Update appointment
$updatedAppointment = $apiService->updateAppointment(1, [
    'status' => 'confirmed'
]);

// Get statistics
$statistics = $apiService->getStatistics();
```

## Notes
- All dates should be in Y-m-d format
- All times should be in H:i format (24-hour)
- The API uses Laravel Sanctum for authentication
- All responses follow a consistent format with success flag, message, and data
- Pagination is included for list endpoints
- Search and filtering capabilities are available for most endpoints 