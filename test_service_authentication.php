<?php

/**
 * Test Service Authentication Endpoint
 * 
 * This script tests the service authentication endpoint to debug the 405 error
 */

// Configuration
$url = 'https://www.bansalimmigration.com.au/api/service-account/authenticate';
$serviceToken = 'wqGUnljhxWyKxgyCZIccqVfcxmZaz5R6ez9DGtjgyBaVLOA6GPBLcFv2VEcheTJY';

$data = [
    'service_token' => $serviceToken
];

echo "=== Testing Service Authentication ===\n";
echo "URL: $url\n";
echo "Service Token: $serviceToken\n";
echo "Data: " . json_encode($data) . "\n\n";

// Test 1: POST request (correct method)
echo "--- Test 1: POST Request ---\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_VERBOSE, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

curl_close($ch);

// Test 2: GET request (to see if it's a method issue)
echo "--- Test 2: GET Request (should fail) ---\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

curl_close($ch);

// Test 3: Check if the route exists
echo "--- Test 3: Check Route Existence ---\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.bansalimmigration.com.au/api/service-account/generate-token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Generate Token Endpoint - HTTP Code: $httpCode\n";
echo "Response: $response\n\n";

curl_close($ch);

echo "=== End ===\n"; 