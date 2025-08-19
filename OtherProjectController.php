<?php

namespace App\Http\Controllers;

use App\Services\AppointmentApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Exception;

class AppointmentController extends Controller
{
    protected $apiService;

    public function __construct()
    {
        $this->apiService = new AppointmentApiService();
    }

    /**
     * Display a listing of appointments
     */
    public function index(Request $request)
    {
        try {
            // Check if we have a cached token
            $cachedToken = Cache::get('appointment_api_token');
            
            if ($cachedToken) {
                $this->apiService->setToken($cachedToken);
                
                // Check if token is still valid
                if (!$this->apiService->isTokenValid()) {
                    // Token expired, need to login again
                    Cache::forget('appointment_api_token');
                    return redirect()->route('appointments.login')->with('error', 'Session expired. Please login again.');
                }
            } else {
                // No cached token, redirect to login
                return redirect()->route('appointments.login');
            }

            // Get appointments with filters
            $params = $request->only(['search', 'status', 'date_from', 'date_to', 'per_page']);
            $appointments = $this->apiService->getAppointments($params);

            return view('appointments.index', compact('appointments'));

        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch appointments: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new appointment
     */
    public function create()
    {
        return view('appointments.create');
    }

    /**
     * Store a newly created appointment
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email',
                'date' => 'required|date',
                'time' => 'required|date_format:H:i',
                'description' => 'required|string',
                'status' => 'required|in:pending,confirmed,cancelled,completed,rescheduled',
                'appointment_details' => 'required|string',
            ]);

            $appointment = $this->apiService->createAppointment($validated);

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment created successfully');

        } catch (Exception $e) {
            return back()->with('error', 'Failed to create appointment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified appointment
     */
    public function show($id)
    {
        try {
            $appointment = $this->apiService->getAppointment($id);
            
            if ($appointment['success']) {
                return view('appointments.show', compact('appointment'));
            } else {
                return back()->with('error', 'Appointment not found');
            }

        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch appointment: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified appointment
     */
    public function edit($id)
    {
        try {
            $appointment = $this->apiService->getAppointment($id);
            
            if ($appointment['success']) {
                return view('appointments.edit', compact('appointment'));
            } else {
                return back()->with('error', 'Appointment not found');
            }

        } catch (Exception $e) {
            return back()->with('error', 'Failed to fetch appointment: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified appointment
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email',
                'date' => 'sometimes|date',
                'time' => 'sometimes|date_format:H:i',
                'description' => 'sometimes|string',
                'status' => 'sometimes|in:pending,confirmed,cancelled,completed,rescheduled',
                'appointment_details' => 'sometimes|string',
            ]);

            $appointment = $this->apiService->updateAppointment($id, $validated);

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment updated successfully');

        } catch (Exception $e) {
            return back()->with('error', 'Failed to update appointment: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified appointment
     */
    public function destroy($id)
    {
        try {
            $this->apiService->deleteAppointment($id);

            return redirect()->route('appointments.index')
                ->with('success', 'Appointment deleted successfully');

        } catch (Exception $e) {
            return back()->with('error', 'Failed to delete appointment: ' . $e->getMessage());
        }
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('appointments.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            $loginResponse = $this->apiService->login($validated['email'], $validated['password']);

            if ($loginResponse['success']) {
                return redirect()->route('appointments.index')
                    ->with('success', 'Login successful');
            } else {
                return back()->with('error', 'Login failed');
            }

        } catch (Exception $e) {
            return back()->with('error', 'Login error: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Handle logout
     */
    public function logout()
    {
        try {
            $this->apiService->logout();
            return redirect()->route('appointments.login')
                ->with('success', 'Logged out successfully');

        } catch (Exception $e) {
            return back()->with('error', 'Logout error: ' . $e->getMessage());
        }
    }

    /**
     * Get appointment statistics
     */
    public function statistics()
    {
        try {
            $stats = $this->apiService->getStatistics();
            
            if ($stats['success']) {
                return view('appointments.statistics', compact('stats'));
            } else {
                return back()->with('error', 'Failed to get statistics');
            }

        } catch (Exception $e) {
            return back()->with('error', 'Failed to get statistics: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update appointment status
     */
    public function bulkUpdateStatus(Request $request)
    {
        try {
            $validated = $request->validate([
                'appointment_ids' => 'required|array',
                'appointment_ids.*' => 'integer',
                'status' => 'required|in:pending,confirmed,cancelled,completed,rescheduled',
            ]);

            $result = $this->apiService->bulkUpdateStatus(
                $validated['appointment_ids'], 
                $validated['status']
            );

            return redirect()->route('appointments.index')
                ->with('success', 'Bulk update completed successfully');

        } catch (Exception $e) {
            return back()->with('error', 'Bulk update failed: ' . $e->getMessage());
        }
    }

    /**
     * Get appointments by date range
     */
    public function dateRange(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $appointments = $this->apiService->getAppointmentsByDateRange(
                $validated['start_date'], 
                $validated['end_date']
            );

            return view('appointments.date-range', compact('appointments'));

        } catch (Exception $e) {
            return back()->with('error', 'Failed to get appointments: ' . $e->getMessage());
        }
    }
} 