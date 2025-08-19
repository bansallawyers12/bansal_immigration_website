<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class AppointmentApiService
{
    protected $baseUrl;
    protected $token;
    protected $tokenExpiry;

    public function __construct($baseUrl = null, $token = null)
    {
        $this->baseUrl = $baseUrl ?? config('services.appointment_api.url', 'http://localhost/bansalimmigration.com.au/api');
        $this->token = $token;
    }

    /**
     * Login to the API and get authentication token
     */
    public function login($email, $password)
    {
        try {
            $response = Http::timeout(30)->post($this->baseUrl . '/login', [
                'email' => $email,
                'password' => $password
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    $this->token = $data['data']['token'];
                    
                    // Cache the token for future use (optional)
                    Cache::put('appointment_api_token', $this->token, now()->addHours(24));
                    
                    return $data;
                } else {
                    throw new Exception($data['message'] ?? 'Login failed');
                }
            }

            throw new Exception('Login request failed: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Login error: ' . $e->getMessage());
        }
    }

    /**
     * Authenticate using service account token (for external applications)
     */
    public function authenticateWithServiceToken($serviceToken)
    {
        try {
            $response = Http::timeout(30)->post($this->baseUrl . '/service-account/authenticate', [
                'service_token' => $serviceToken
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if ($data['success']) {
                    $this->token = $data['data']['token'];
                    
                    // Cache the token for future use
                    Cache::put('appointment_api_token', $this->token, now()->addHours(24));
                    
                    return $data;
                } else {
                    throw new Exception($data['message'] ?? 'Service authentication failed');
                }
            }

            throw new Exception('Service authentication request failed: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Service authentication error: ' . $e->getMessage());
        }
    }

    /**
     * Get all appointments with optional filters
     */
    public function getAppointments($params = [])
    {
        $this->ensureToken();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->get($this->baseUrl . '/appointments', $params);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to get appointments: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Get appointments error: ' . $e->getMessage());
        }
    }

    /**
     * Get a single appointment by ID
     */
    public function getAppointment($id)
    {
        $this->ensureToken();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->get($this->baseUrl . '/appointments/' . $id);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to get appointment: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Get appointment error: ' . $e->getMessage());
        }
    }

    /**
     * Create a new appointment
     */
    public function createAppointment($data)
    {
        $this->ensureToken();
        
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
     * Update an existing appointment
     */
    public function updateAppointment($id, $data)
    {
        $this->ensureToken();
        
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
     * Delete an appointment
     */
    public function deleteAppointment($id)
    {
        $this->ensureToken();
        
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
     * Get appointment statistics
     */
    public function getStatistics()
    {
        $this->ensureToken();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->get($this->baseUrl . '/appointments/statistics/overview');

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to get statistics: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Get statistics error: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update appointment status
     */
    public function bulkUpdateStatus($appointmentIds, $status)
    {
        $this->ensureToken();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->post($this->baseUrl . '/appointments/bulk-update-status', [
                    'appointment_ids' => $appointmentIds,
                    'status' => $status
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to bulk update status: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Bulk update error: ' . $e->getMessage());
        }
    }

    /**
     * Get appointments by date range
     */
    public function getAppointmentsByDateRange($startDate, $endDate)
    {
        $this->ensureToken();
        
        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->get($this->baseUrl . '/appointments/date-range/search', [
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to get appointments by date range: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Date range search error: ' . $e->getMessage());
        }
    }

    /**
     * Logout and revoke token
     */
    public function logout()
    {
        if (!$this->token) {
            return ['success' => true, 'message' => 'Already logged out'];
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(30)
                ->post($this->baseUrl . '/logout');

            // Clear cached token
            Cache::forget('appointment_api_token');
            $this->token = null;

            if ($response->successful()) {
                return $response->json();
            }

            throw new Exception('Failed to logout: ' . $response->status());
        } catch (Exception $e) {
            throw new Exception('Logout error: ' . $e->getMessage());
        }
    }

    /**
     * Set token manually (useful for testing or when you have a stored token)
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get current token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Ensure we have a valid token
     */
    protected function ensureToken()
    {
        if (!$this->token) {
            // Try to get from cache
            $cachedToken = Cache::get('appointment_api_token');
            if ($cachedToken) {
                $this->token = $cachedToken;
            } else {
                throw new Exception('No authentication token available. Please login first.');
            }
        }
    }

    /**
     * Check if token is valid
     */
    public function isTokenValid()
    {
        if (!$this->token) {
            return false;
        }

        try {
            $response = Http::withToken($this->token)
                ->timeout(10)
                ->get($this->baseUrl . '/user');

            return $response->successful();
        } catch (Exception $e) {
            return false;
        }
    }
} 