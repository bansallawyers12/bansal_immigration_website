<?php
/**
 * Website Configuration for Appointment API Integration
 * 
 * Update these settings with your actual API server details
 */

// API Server Configuration
define('APPOINTMENT_API_URL', 'http://localhost/bansalimmigration.com.au/api');
define('APPOINTMENT_ADMIN_EMAIL', 'ankitbansal0011@gmail.au');
define('APPOINTMENT_ADMIN_PASSWORD', 'your_password_here');

// Website Configuration
define('WEBSITE_NAME', 'Bansal Immigration');
define('WEBSITE_URL', 'https://your-website.com');
define('WEBSITE_LOGO', 'https://your-website.com/logo.png');

// Display Settings
define('APPOINTMENTS_PER_PAGE', 10);
define('AUTO_REFRESH_INTERVAL', 300); // 5 minutes in seconds
define('SHOW_STATISTICS', true);
define('SHOW_SEARCH_FORM', true);

// Security Settings
define('ENABLE_HTTPS', true);
define('API_TIMEOUT', 30);
define('CACHE_ENABLED', true);
define('CACHE_DURATION', 3600); // 1 hour

// Available Admin Accounts (for reference)
$availableAdmins = [
    [
        'email' => 'bansalcrm@gmail.com',
        'name' => 'Admin',
        'client_id' => null
    ],
    [
        'email' => 'ankitbansal0011@gmail.au',
        'name' => 'Ankit Bansal',
        'client_id' => 'ANKI22112'
    ],
    [
        'email' => 'arunbnsal@gmail.com',
        'name' => 'test test',
        'client_id' => 'TEST22103'
    ],
    [
        'email' => 'amandhillon79172@gmail.com',
        'name' => 'Amandeep Kaur',
        'client_id' => 'AMAN22104'
    ],
    [
        'email' => 'skirandeep693@gmail.com',
        'name' => 'Kirandeep Singh',
        'client_id' => 'KIRA22105'
    ]
];

// Error Messages
$errorMessages = [
    'login_failed' => 'Unable to connect to the appointment system. Please check your credentials.',
    'connection_failed' => 'Cannot connect to the appointment server. Please try again later.',
    'no_appointments' => 'No appointments found for the selected criteria.',
    'api_error' => 'An error occurred while fetching appointments. Please try again.',
    'invalid_token' => 'Your session has expired. Please refresh the page.'
];

// Status Colors for Bootstrap
$statusColors = [
    'pending' => 'warning',
    'confirmed' => 'success',
    'completed' => 'info',
    'cancelled' => 'danger',
    'rescheduled' => 'secondary'
];

// Table Columns Configuration
$tableColumns = [
    'id' => ['label' => '#', 'sortable' => true],
    'full_name' => ['label' => 'Client', 'sortable' => true],
    'date' => ['label' => 'Date', 'sortable' => true],
    'time' => ['label' => 'Time', 'sortable' => true],
    'nature_of_enquiry' => ['label' => 'Nature of Enquiry', 'sortable' => true],
    'description' => ['label' => 'Description', 'sortable' => false],
    'user' => ['label' => 'Added By', 'sortable' => true],
    'status' => ['label' => 'Status', 'sortable' => true],
    'actions' => ['label' => 'Action', 'sortable' => false]
];

// Search Filters
$searchFilters = [
    'date_search' => [
        'type' => 'date',
        'label' => 'Appointment Date',
        'placeholder' => 'Select date'
    ],
    'search' => [
        'type' => 'text',
        'label' => 'Client/Description',
        'placeholder' => 'Search with Client reference, description'
    ],
    'status' => [
        'type' => 'select',
        'label' => 'Status',
        'options' => [
            '' => 'All Status',
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'rescheduled' => 'Rescheduled'
        ]
    ],
    'per_page' => [
        'type' => 'select',
        'label' => 'Per Page',
        'options' => [
            '10' => '10',
            '25' => '25',
            '50' => '50',
            '100' => '100'
        ]
    ]
];

// API Endpoints
$apiEndpoints = [
    'login' => '/login',
    'appointments' => '/appointments',
    'statistics' => '/appointments/statistics/overview',
    'create' => '/appointments',
    'update' => '/appointments/{id}',
    'delete' => '/appointments/{id}',
    'date_range' => '/appointments/date-range/search',
    'bulk_update' => '/appointments/bulk-update-status'
];

// Cache Configuration
$cacheConfig = [
    'enabled' => CACHE_ENABLED,
    'duration' => CACHE_DURATION,
    'prefix' => 'appointments_',
    'keys' => [
        'token' => 'api_token',
        'appointments' => 'appointments_list',
        'statistics' => 'appointments_stats'
    ]
];

// Logging Configuration
$loggingConfig = [
    'enabled' => true,
    'file' => 'appointments_api.log',
    'level' => 'ERROR', // DEBUG, INFO, WARNING, ERROR
    'max_size' => 10485760 // 10MB
];

// Email Configuration (for notifications)
$emailConfig = [
    'enabled' => false,
    'from_email' => 'noreply@your-website.com',
    'from_name' => 'Appointment System',
    'admin_email' => 'admin@your-website.com'
];

// Mobile Responsive Settings
$mobileConfig = [
    'enabled' => true,
    'breakpoint' => 'md',
    'table_scroll' => true,
    'cards_view' => true
];

// Export Settings
$exportConfig = [
    'enabled' => true,
    'formats' => ['csv', 'pdf', 'excel'],
    'max_records' => 1000
];

// Real-time Updates (WebSocket/SSE)
$realtimeConfig = [
    'enabled' => false,
    'type' => 'polling', // polling, websocket, sse
    'interval' => 30000 // 30 seconds
];

// Customization
$customization = [
    'theme' => 'default', // default, dark, light, custom
    'primary_color' => '#007bff',
    'secondary_color' => '#6c757d',
    'font_family' => 'Arial, sans-serif',
    'show_logo' => true,
    'show_footer' => true,
    'show_breadcrumbs' => true
];

// Performance Settings
$performanceConfig = [
    'enable_compression' => true,
    'enable_caching' => true,
    'minify_css' => true,
    'minify_js' => true,
    'lazy_loading' => true,
    'image_optimization' => true
];

// Security Settings
$securityConfig = [
    'csrf_protection' => true,
    'xss_protection' => true,
    'content_security_policy' => true,
    'rate_limiting' => true,
    'max_requests_per_minute' => 60,
    'allowed_origins' => ['https://your-website.com']
];

// Debug Settings
$debugConfig = [
    'enabled' => false,
    'show_errors' => false,
    'log_queries' => false,
    'show_performance' => false
];

// Environment Detection
function isProduction() {
    return !in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1']);
}

function isLocalhost() {
    return in_array($_SERVER['HTTP_HOST'] ?? '', ['localhost', '127.0.0.1', '::1']);
}

// Auto-adjust settings based on environment
if (isProduction()) {
    define('ENABLE_HTTPS', true);
    define('DEBUG_MODE', false);
    define('CACHE_ENABLED', true);
} else {
    define('ENABLE_HTTPS', false);
    define('DEBUG_MODE', true);
    define('CACHE_ENABLED', false);
}

// Helper Functions
function getApiUrl($endpoint = '') {
    $baseUrl = APPOINTMENT_API_URL;
    if (ENABLE_HTTPS && strpos($baseUrl, 'http://') === 0) {
        $baseUrl = str_replace('http://', 'https://', $baseUrl);
    }
    return $baseUrl . $endpoint;
}

function getStatusColor($status) {
    global $statusColors;
    return $statusColors[strtolower($status)] ?? 'primary';
}

function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

function formatTime($time) {
    return date('h:i A', strtotime($time));
}

function truncateText($text, $length = 50) {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . '...';
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

function logError($message, $context = []) {
    if ($loggingConfig['enabled']) {
        $logEntry = date('Y-m-d H:i:s') . ' - ERROR: ' . $message;
        if (!empty($context)) {
            $logEntry .= ' - Context: ' . json_encode($context);
        }
        file_put_contents($loggingConfig['file'], $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

function logInfo($message, $context = []) {
    if ($loggingConfig['enabled'] && $loggingConfig['level'] === 'INFO') {
        $logEntry = date('Y-m-d H:i:s') . ' - INFO: ' . $message;
        if (!empty($context)) {
            $logEntry .= ' - Context: ' . json_encode($context);
        }
        file_put_contents($loggingConfig['file'], $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}
?> 