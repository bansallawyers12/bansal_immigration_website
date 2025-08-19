# Website Integration Setup Guide

## 🌐 Complete Website Integration for Appointment API

This guide shows you how to integrate your appointment APIs into any website on a different domain/server.

## 📋 Prerequisites

- Your API server is running and accessible
- Your website has PHP support
- Both servers can communicate over HTTP/HTTPS
- cURL extension enabled on your website server

## 🚀 Quick Start (5 Minutes)

### Step 1: Download Files
Copy these files to your website:
- `website_integration.php` - Main integration class
- `appointments_page_example.php` - Complete example page
- `website_config.php` - Configuration file

### Step 2: Update Configuration
Edit `website_config.php`:
```php
define('APPOINTMENT_API_URL', 'http://your-api-server.com/api');
define('APPOINTMENT_ADMIN_EMAIL', 'ankitbansal0011@gmail.au');
define('APPOINTMENT_ADMIN_PASSWORD', 'your_actual_password');
```

### Step 3: Test the Integration
Access `appointments_page_example.php` in your browser to see the appointments!

## 📁 File Structure

```
your-website/
├── appointments/
│   ├── website_integration.php
│   ├── appointments_page_example.php
│   ├── website_config.php
│   └── index.php (optional - redirect to example)
├── css/
├── js/
└── index.html
```

## 🔧 Detailed Setup

### 1. Basic Integration

Create a simple page to display appointments:

```php
<?php
require_once 'website_integration.php';

// Configuration
$apiUrl = 'http://your-api-server.com/api';
$adminEmail = 'ankitbansal0011@gmail.au';
$adminPassword = 'your_password';

// Initialize
$appointmentAPI = new AppointmentWebsiteIntegration($apiUrl, $adminEmail, $adminPassword);

// Get appointments
$appointments = $appointmentAPI->getAppointments();

// Display
if (isset($appointments['data']['data'])) {
    echo AppointmentHTMLHelper::displayAppointmentsTable($appointments);
} else {
    echo "Error: " . ($appointments['error'] ?? 'Unknown error');
}
?>
```

### 2. Advanced Integration with Search

```php
<?php
require_once 'website_integration.php';

// Get search parameters
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';

// Build filters
$filters = [];
if ($search) $filters['search'] = $search;
if ($status) $filters['status'] = $status;

// Get appointments
$appointments = $appointmentAPI->getAppointments($filters);

// Display search form and results
echo AppointmentHTMLHelper::displaySearchForm();
echo AppointmentHTMLHelper::displayAppointmentsTable($appointments);
?>
```

### 3. Integration with Existing Website

Add this to your existing website page:

```php
<?php
// Include the integration
require_once 'path/to/website_integration.php';

// Initialize API
$appointmentAPI = new AppointmentWebsiteIntegration(
    'http://your-api-server.com/api',
    'ankitbansal0011@gmail.au',
    'your_password'
);

// Get appointments
$appointments = $appointmentAPI->getAppointments();

// Display in your existing layout
?>
<div class="your-existing-container">
    <h2>Appointments</h2>
    <?php echo AppointmentHTMLHelper::displayAppointmentsTable($appointments); ?>
</div>
```

## 🎨 Customization Options

### 1. Custom Styling

Add your own CSS to match your website theme:

```css
/* Custom appointment table styling */
.appointment-table {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.appointment-table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}
```

### 2. Custom JavaScript

Add interactive features:

```javascript
// Custom appointment functions
function viewAppointmentDetails(id) {
    // Fetch appointment details via AJAX
    fetch(`/api/appointments/${id}`)
        .then(response => response.json())
        .then(data => {
            // Show modal with details
            showAppointmentModal(data);
        });
}

function updateAppointmentStatus(id, status) {
    // Update appointment status
    fetch(`/api/appointments/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
```

### 3. Custom Layout

Create your own layout template:

```php
<!DOCTYPE html>
<html>
<head>
    <title>Your Website - Appointments</title>
    <link rel="stylesheet" href="your-custom-styles.css">
</head>
<body>
    <!-- Your website header -->
    <?php include 'header.php'; ?>
    
    <!-- Appointments content -->
    <div class="main-content">
        <h1>Appointments</h1>
        <?php echo AppointmentHTMLHelper::displayAppointmentsTable($appointments); ?>
    </div>
    
    <!-- Your website footer -->
    <?php include 'footer.php'; ?>
</body>
</html>
```

## 🔒 Security Considerations

### 1. HTTPS in Production
Always use HTTPS in production:
```php
define('APPOINTMENT_API_URL', 'https://your-api-server.com/api');
```

### 2. Secure Credentials
Store credentials securely:
```php
// Use environment variables or secure config files
$adminEmail = getenv('APPOINTMENT_ADMIN_EMAIL');
$adminPassword = getenv('APPOINTMENT_ADMIN_PASSWORD');
```

### 3. Input Validation
Always validate user inputs:
```php
$search = filter_var($_GET['search'] ?? '', FILTER_SANITIZE_STRING);
$status = in_array($_GET['status'] ?? '', ['pending', 'confirmed', 'completed']) ? $_GET['status'] : '';
```

## 📱 Mobile Responsive

The integration includes Bootstrap for mobile responsiveness. For custom mobile styling:

```css
/* Mobile-specific styles */
@media (max-width: 768px) {
    .appointment-table {
        font-size: 14px;
    }
    
    .btn-sm {
        padding: 2px 4px;
        font-size: 12px;
    }
    
    .table-responsive {
        border: none;
    }
}
```

## 🔄 Auto-Refresh

Enable automatic page refresh:

```javascript
// Refresh every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);

// Or refresh specific content via AJAX
function refreshAppointments() {
    fetch('/appointments?ajax=1')
        .then(response => response.text())
        .then(html => {
            document.getElementById('appointments-container').innerHTML = html;
        });
}
```

## 📊 Statistics Display

Show appointment statistics:

```php
// Get statistics
$statistics = $appointmentAPI->getStatistics();

// Display statistics cards
echo AppointmentHTMLHelper::displayStatistics($statistics);
```

## 🎯 Common Use Cases

### 1. Dashboard Widget
```php
// Show recent appointments on dashboard
$recentAppointments = $appointmentAPI->getAppointments(['per_page' => 5]);
echo "<h3>Recent Appointments</h3>";
echo AppointmentHTMLHelper::displayAppointmentsTable($recentAppointments);
```

### 2. Status Overview
```php
// Show status counts
$stats = $appointmentAPI->getStatistics();
echo "<div class='status-overview'>";
echo "<span>Pending: {$stats['data']['pending']}</span>";
echo "<span>Confirmed: {$stats['data']['confirmed']}</span>";
echo "<span>Completed: {$stats['data']['completed']}</span>";
echo "</div>";
```

### 3. Calendar Integration
```php
// Get appointments for specific date range
$dateRangeAppointments = $appointmentAPI->getAppointmentsByDateRange(
    '2024-01-01', 
    '2024-01-31'
);
```

## 🛠️ Troubleshooting

### Common Issues:

**1. Connection Failed**
- Check if API server is accessible
- Verify URL in configuration
- Check firewall settings

**2. Authentication Failed**
- Verify email and password
- Check if admin account exists
- Ensure API server is using correct database

**3. No Appointments Displayed**
- Check if appointments exist in database
- Verify API response format
- Check for JavaScript errors

**4. Styling Issues**
- Ensure Bootstrap CSS is loaded
- Check for CSS conflicts
- Verify responsive breakpoints

### Debug Mode:
```php
// Enable debug mode
define('DEBUG_MODE', true);

// Check API response
var_dump($appointments);
```

## 📈 Performance Optimization

### 1. Caching
```php
// Implement caching
$cacheKey = 'appointments_' . md5(serialize($filters));
$cached = cache_get($cacheKey);

if ($cached) {
    $appointments = $cached;
} else {
    $appointments = $appointmentAPI->getAppointments($filters);
    cache_set($cacheKey, $appointments, 300); // 5 minutes
}
```

### 2. Lazy Loading
```javascript
// Load appointments on demand
function loadAppointments(page = 1) {
    fetch(`/appointments?page=${page}&ajax=1`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('appointments-list').innerHTML = html;
        });
}
```

## 🔗 API Endpoints Reference

| Endpoint | Method | Description |
|----------|--------|-------------|
| `/login` | POST | Authenticate and get token |
| `/appointments` | GET | Get appointments with filters |
| `/appointments/{id}` | GET | Get single appointment |
| `/appointments` | POST | Create new appointment |
| `/appointments/{id}` | PUT | Update appointment |
| `/appointments/{id}` | DELETE | Delete appointment |
| `/appointments/statistics/overview` | GET | Get statistics |
| `/appointments/date-range/search` | GET | Search by date range |
| `/appointments/bulk-update-status` | POST | Bulk update status |

## 📞 Support

If you encounter issues:
1. Check the error messages in your browser console
2. Verify the API server is accessible
3. Test the API endpoints directly with Postman
4. Check the network connectivity between servers

## 🎉 Success!

Once integrated, you'll have:
- ✅ Real-time appointment display
- ✅ Search and filtering capabilities
- ✅ Mobile responsive design
- ✅ Secure API communication
- ✅ Statistics and overview
- ✅ Professional appearance

Your website will now display appointments from your API server seamlessly! 