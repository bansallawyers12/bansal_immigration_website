<?php

/**
 * Service Token Generator for Appointment API Integration
 * 
 * This script generates a service token for integrating with the appointment API
 * from bansalimmigration.com.au to migrationmanager.bansalcrm.com
 */

// Configuration
$url = 'http://127.0.0.1:8000/api/service-account/generate-token';
$data = [
    'service_name' => 'Migration Manager CRM',
    'description' => 'Integration for migrationmanager.bansalcrm.com',
    'admin_email' => 'admin1@gmail.com',   // Admin email from database
    'admin_password' => '123456'              // Admin password
];

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For development only

// Execute the request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for cURL errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch) . "\n";
    exit(1);
}

curl_close($ch);

// Parse response
$result = json_decode($response, true);

echo "=== Service Token Generation Result ===\n";
echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n\n";

if ($httpCode === 200 && isset($result['success']) && $result['success']) {
    echo "✅ SUCCESS! Service token generated successfully.\n\n";
    echo "🔑 SERVICE TOKEN:\n";
    echo $result['data']['service_account']['token'] . "\n\n";
    
    echo "📝 Add this to your migrationmanager.bansalcrm.com .env file:\n";
    echo "APPOINTMENT_API_SERVICE_TOKEN=" . $result['data']['service_account']['token'] . "\n\n";
    
    echo "📋 Service Account Details:\n";
    echo "ID: " . $result['data']['service_account']['id'] . "\n";
    echo "Name: " . $result['data']['service_account']['service_name'] . "\n";
    echo "Created: " . $result['data']['service_account']['created_at'] . "\n";
} else {
    echo "❌ ERROR: Failed to generate service token.\n";
    if (isset($result['message'])) {
        echo "Message: " . $result['message'] . "\n";
    }
    if (isset($result['errors'])) {
        echo "Errors: " . print_r($result['errors'], true) . "\n";
    }
}

echo "\n=== End ===\n"; 