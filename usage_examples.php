<?php

// Usage Examples for AppointmentApiService

use App\Services\AppointmentApiService;

// Example 1: Basic Usage
$apiService = new AppointmentApiService();

// Login
try {
    $loginResponse = $apiService->login('ankitbansal0011@gmail.au', 'your_password');
    echo "Login successful!\n";
} catch (Exception $e) {
    echo "Login failed: " . $e->getMessage() . "\n";
}

// Get all appointments
try {
    $appointments = $apiService->getAppointments([
        'status' => 'confirmed',
        'per_page' => 10
    ]);
    echo "Found " . count($appointments['data']['data']) . " appointments\n";
} catch (Exception $e) {
    echo "Failed to get appointments: " . $e->getMessage() . "\n";
}

// Example 2: Create Appointment
try {
    $newAppointment = $apiService->createAppointment([
        'full_name' => 'John Doe',
        'email' => 'john@example.com',
        'date' => '2024-01-15',
        'time' => '10:00',
        'description' => 'Consultation meeting',
        'status' => 'pending',
        'appointment_details' => 'Detailed notes about the appointment'
    ]);
    echo "Appointment created with ID: " . $newAppointment['data']['id'] . "\n";
} catch (Exception $e) {
    echo "Failed to create appointment: " . $e->getMessage() . "\n";
}

// Example 3: Update Appointment
try {
    $updatedAppointment = $apiService->updateAppointment(1, [
        'status' => 'confirmed',
        'description' => 'Updated consultation meeting'
    ]);
    echo "Appointment updated successfully\n";
} catch (Exception $e) {
    echo "Failed to update appointment: " . $e->getMessage() . "\n";
}

// Example 4: Get Statistics
try {
    $statistics = $apiService->getStatistics();
    echo "Total appointments: " . $statistics['data']['total'] . "\n";
    echo "Pending: " . $statistics['data']['pending'] . "\n";
    echo "Confirmed: " . $statistics['data']['confirmed'] . "\n";
} catch (Exception $e) {
    echo "Failed to get statistics: " . $e->getMessage() . "\n";
}

// Example 5: Bulk Update
try {
    $result = $apiService->bulkUpdateStatus([1, 2, 3], 'confirmed');
    echo "Bulk update completed. Updated " . $result['data']['updated_count'] . " appointments\n";
} catch (Exception $e) {
    echo "Failed to bulk update: " . $e->getMessage() . "\n";
}

// Example 6: Date Range Search
try {
    $appointments = $apiService->getAppointmentsByDateRange('2024-01-01', '2024-01-31');
    echo "Found " . count($appointments['data']) . " appointments in date range\n";
} catch (Exception $e) {
    echo "Failed to get appointments by date range: " . $e->getMessage() . "\n";
}

// Example 7: Using with Custom Base URL
$customApiService = new AppointmentApiService('http://your-custom-server.com/api');

// Example 8: Using with Pre-existing Token
$apiServiceWithToken = new AppointmentApiService();
$apiServiceWithToken->setToken('your_existing_token_here');

// Example 9: Check Token Validity
if ($apiService->isTokenValid()) {
    echo "Token is valid\n";
} else {
    echo "Token is invalid or expired\n";
}

// Example 10: Logout
try {
    $apiService->logout();
    echo "Logged out successfully\n";
} catch (Exception $e) {
    echo "Logout failed: " . $e->getMessage() . "\n";
}

// Example 11: Using in a Job/Queue
class ProcessAppointmentsJob
{
    public function handle()
    {
        $apiService = new AppointmentApiService();
        
        // Login (you might want to store credentials securely)
        $apiService->login('your_email@example.com', 'your_password');
        
        // Process appointments
        $appointments = $apiService->getAppointments(['status' => 'pending']);
        
        foreach ($appointments['data']['data'] as $appointment) {
            // Process each appointment
            echo "Processing appointment: " . $appointment['id'] . "\n";
        }
    }
}

// Example 12: Using in a Command
class SyncAppointmentsCommand
{
    public function handle()
    {
        $apiService = new AppointmentApiService();
        
        try {
            // Login
            $apiService->login('your_email@example.com', 'your_password');
            
            // Get all appointments
            $appointments = $apiService->getAppointments(['per_page' => 100]);
            
            // Sync to local database
            foreach ($appointments['data']['data'] as $appointment) {
                // Sync logic here
                echo "Syncing appointment: " . $appointment['id'] . "\n";
            }
            
        } catch (Exception $e) {
            echo "Sync failed: " . $e->getMessage() . "\n";
        }
    }
} 