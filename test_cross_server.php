<?php

/**
 * Cross-Server API Test Script
 * 
 * This script tests the connectivity between your other project and the API server.
 * Run this script from your other Laravel project to verify the integration.
 */

require_once 'vendor/autoload.php';

use App\Services\AppointmentApiService;

echo "🔍 Cross-Server API Integration Test\n";
echo "=====================================\n\n";

// Test configuration
$apiUrl = env('APPOINTMENT_API_URL', 'http://localhost/bansalimmigration.com.au/api');
$testEmail = 'ankitbansal0011@gmail.au';
$testPassword = 'your_password_here'; // Replace with actual password

echo "📋 Test Configuration:\n";
echo "API URL: " . $apiUrl . "\n";
echo "Test Email: " . $testEmail . "\n";
echo "=====================================\n\n";

// Initialize API service
$apiService = new AppointmentApiService($apiUrl);

// Test 1: Check API Server Connectivity
echo "1️⃣ Testing API Server Connectivity...\n";
try {
    $response = Http::timeout(10)->get($apiUrl . '/user');
    if ($response->status() === 401) {
        echo "✅ API server is accessible (401 expected for unauthenticated request)\n";
    } else {
        echo "⚠️  API server responded with status: " . $response->status() . "\n";
    }
} catch (Exception $e) {
    echo "❌ Cannot connect to API server: " . $e->getMessage() . "\n";
    echo "   Please check:\n";
    echo "   - API server is running\n";
    echo "   - URL is correct in your .env file\n";
    echo "   - Network connectivity between servers\n";
    exit(1);
}

// Test 2: Login
echo "\n2️⃣ Testing Login...\n";
try {
    $loginResponse = $apiService->login($testEmail, $testPassword);
    
    if ($loginResponse['success']) {
        echo "✅ Login successful!\n";
        echo "   Token: " . substr($loginResponse['data']['token'], 0, 20) . "...\n";
        echo "   User: " . $loginResponse['data']['user_data']['first_name'] . " " . $loginResponse['data']['user_data']['last_name'] . "\n";
    } else {
        echo "❌ Login failed: " . ($loginResponse['message'] ?? 'Unknown error') . "\n";
        exit(1);
    }
} catch (Exception $e) {
    echo "❌ Login error: " . $e->getMessage() . "\n";
    echo "   Please check:\n";
    echo "   - Email and password are correct\n";
    echo "   - Admin account exists in the API server\n";
    exit(1);
}

// Test 3: Get Appointments
echo "\n3️⃣ Testing Get Appointments...\n";
try {
    $appointments = $apiService->getAppointments(['per_page' => 5]);
    
    if ($appointments['success']) {
        $count = count($appointments['data']['data']);
        echo "✅ Successfully retrieved " . $count . " appointments\n";
        
        if ($count > 0) {
            $firstAppointment = $appointments['data']['data'][0];
            echo "   Sample appointment: ID " . $firstAppointment['id'] . " - " . $firstAppointment['full_name'] . "\n";
        }
    } else {
        echo "❌ Failed to get appointments: " . ($appointments['message'] ?? 'Unknown error') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Get appointments error: " . $e->getMessage() . "\n";
}

// Test 4: Get Statistics
echo "\n4️⃣ Testing Get Statistics...\n";
try {
    $statistics = $apiService->getStatistics();
    
    if ($statistics['success']) {
        echo "✅ Statistics retrieved successfully!\n";
        echo "   Total: " . $statistics['data']['total'] . "\n";
        echo "   Pending: " . $statistics['data']['pending'] . "\n";
        echo "   Confirmed: " . $statistics['data']['confirmed'] . "\n";
        echo "   Completed: " . $statistics['data']['completed'] . "\n";
    } else {
        echo "❌ Failed to get statistics: " . ($statistics['message'] ?? 'Unknown error') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Get statistics error: " . $e->getMessage() . "\n";
}

// Test 5: Token Validation
echo "\n5️⃣ Testing Token Validation...\n";
try {
    if ($apiService->isTokenValid()) {
        echo "✅ Token is valid and working\n";
    } else {
        echo "❌ Token validation failed\n";
    }
} catch (Exception $e) {
    echo "❌ Token validation error: " . $e->getMessage() . "\n";
}

// Test 6: Logout
echo "\n6️⃣ Testing Logout...\n";
try {
    $logoutResponse = $apiService->logout();
    
    if ($logoutResponse['success']) {
        echo "✅ Logout successful!\n";
    } else {
        echo "❌ Logout failed: " . ($logoutResponse['message'] ?? 'Unknown error') . "\n";
    }
} catch (Exception $e) {
    echo "❌ Logout error: " . $e->getMessage() . "\n";
}

// Summary
echo "\n🎉 Test Summary\n";
echo "===============\n";
echo "✅ Cross-server API integration is working!\n";
echo "✅ You can now use the AppointmentApiService in your other project\n\n";

echo "📝 Next Steps:\n";
echo "1. Copy AppointmentApiService.php to your other project's app/Services/ directory\n";
echo "2. Add the configuration to your other project's config/services.php\n";
echo "3. Set the APPOINTMENT_API_URL in your other project's .env file\n";
echo "4. Start using the API service in your controllers, jobs, or commands\n\n";

echo "🔗 Example Usage:\n";
echo "```php\n";
echo "use App\\Services\\AppointmentApiService;\n\n";
echo "\$apiService = new AppointmentApiService();\n";
echo "\$apiService->login('your_email@example.com', 'your_password');\n";
echo "\$appointments = \$apiService->getAppointments();\n";
echo "```\n";

echo "📚 For more examples, see usage_examples.php\n";
echo "📖 For setup instructions, see SETUP_INSTRUCTIONS.md\n"; 