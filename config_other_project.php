<?php

// Add this to your other Laravel project's config/services.php file

return [
    // ... other services ...

    'appointment_api' => [
        'url' => env('APPOINTMENT_API_URL', 'http://your-api-server.com/api'),
        'timeout' => env('APPOINTMENT_API_TIMEOUT', 30),
        'retry_attempts' => env('APPOINTMENT_API_RETRY_ATTEMPTS', 3),
    ],

    // ... other services ...
]; 