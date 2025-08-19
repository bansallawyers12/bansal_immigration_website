# Service Token Generation Guide

## 🎯 Overview

This guide shows you how to generate service tokens for different users with different admin credentials. You can use any of these methods based on your preference.

## 📋 Methods to Generate Service Token

### Method 1: Using PHP Script (Command Line)

**Step 1:** Run the script with your credentials:

```bash
# Basic usage with default values
php generate_service_token.php

# With custom credentials
php generate_service_token.php "your-email@domain.com" "your-password" "Your Service Name" "Your Description"

# Examples:
php generate_service_token.php admin1@gmail.com 123456 "Company Website" "Integration for company website"
php generate_service_token.php admin2@gmail.com password123 "Client Portal" "Integration for client portal"
```

**Step 2:** Copy the generated token to your other Laravel project's `.env` file:

```env
APPOINTMENT_API_SERVICE_TOKEN=your_generated_token_here
```

### Method 2: Using Laravel Artisan Command

**Step 1:** Run the Artisan command:

```bash
# Basic usage (will prompt for email/password)
php artisan appointment:generate-token

# With all parameters
php artisan appointment:generate-token --email=admin1@gmail.com --password=123456 --service-name="My Website" --description="Integration for my website"

# Examples:
php artisan appointment:generate-token --email=admin1@gmail.com --password=123456 --service-name="Company Website"
php artisan appointment:generate-token --email=admin2@gmail.com --password=password123 --service-name="Client Portal" --description="Integration for client portal"
```

### Method 3: Using Web Interface (Recommended for Non-Technical Users)

**Step 1:** Add this route to your `routes/web.php`:

```php
Route::middleware('auth')->group(function () {
    Route::get('/admin/generate-token', [App\Http\Controllers\Admin\ServiceTokenController::class, 'showGenerateForm'])->name('admin.generate.token.form');
    Route::post('/admin/generate-token', [App\Http\Controllers\Admin\ServiceTokenController::class, 'generateToken'])->name('admin.generate.token');
});
```

**Step 2:** Visit the web interface:
```
https://migrationmanager.bansalcrm.com/admin/generate-token
```

**Step 3:** Fill in the form with your credentials and submit.

### Method 4: Using cURL Command

**Step 1:** Run the cURL command:

```bash
curl -X POST https://migrationmanager.bansalcrm.com/api/service-account/generate-token \
  -H "Content-Type: application/json" \
  -d '{
    "service_name": "Your Service Name",
    "description": "Your Description",
    "admin_email": "your-email@domain.com",
    "admin_password": "your-password"
  }'
```

**Step 2:** Copy the token from the response.

### Method 5: Using Postman or API Testing Tool

**Request Details:**
- **Method:** POST
- **URL:** `https://migrationmanager.bansalcrm.com/api/service-account/generate-token`
- **Headers:** `Content-Type: application/json`
- **Body:**
```json
{
  "service_name": "Your Service Name",
  "description": "Your Description", 
  "admin_email": "your-email@domain.com",
  "admin_password": "your-password"
}
```

## 🔧 Examples for Different Users

### Example 1: Company Website Integration
```bash
php generate_service_token.php admin1@gmail.com 123456 "Company Website" "Integration for company website"
```

### Example 2: Client Portal Integration
```bash
php generate_service_token.php admin2@gmail.com password123 "Client Portal" "Integration for client portal"
```

### Example 3: Mobile App Integration
```bash
php generate_service_token.php mobile-admin@gmail.com mobilepass "Mobile App" "Integration for mobile application"
```

### Example 4: Third-Party Service Integration
```bash
php generate_service_token.php api-admin@gmail.com apipassword "Third Party Service" "Integration for third-party service"
```

## 🛡️ Security Best Practices

### 1. Use Strong Passwords
- Use complex passwords for admin accounts
- Change passwords regularly
- Use different passwords for different admin accounts

### 2. Token Management
- Generate separate tokens for different services
- Use descriptive service names
- Keep tokens secure and don't share them

### 3. Environment Variables
Always store tokens in `.env` files:
```env
APPOINTMENT_API_SERVICE_TOKEN=your_secure_token_here
```

### 4. Token Rotation
Regularly rotate your service tokens:
```bash
# Generate new token
php generate_service_token.php admin1@gmail.com 123456 "Updated Integration" "Updated integration token"
```

## 📊 Token Management

### View All Service Accounts
```bash
# Using Artisan command (if implemented)
php artisan appointment:list-tokens

# Or check the database directly
php artisan tinker
>>> App\ServiceAccount::all();
```

### Deactivate a Token
```bash
# Using Artisan command (if implemented)
php artisan appointment:deactivate-token --id=1
```

## 🎯 Common Scenarios

### Scenario 1: Multiple Websites
If you have multiple websites that need access:

```bash
# Website 1
php generate_service_token.php admin1@gmail.com 123456 "Website 1" "Integration for website 1"

# Website 2  
php generate_service_token.php admin2@gmail.com password123 "Website 2" "Integration for website 2"

# Website 3
php generate_service_token.php admin3@gmail.com securepass "Website 3" "Integration for website 3"
```

### Scenario 2: Different Environments
For different environments (development, staging, production):

```bash
# Development
php generate_service_token.php dev-admin@gmail.com devpass "Development" "Development environment integration"

# Staging
php generate_service_token.php staging-admin@gmail.com stagingpass "Staging" "Staging environment integration"

# Production
php generate_service_token.php prod-admin@gmail.com prodpass "Production" "Production environment integration"
```

### Scenario 3: Different User Roles
For different user roles with different permissions:

```bash
# Admin user
php generate_service_token.php admin@gmail.com adminpass "Admin Integration" "Full admin access"

# Manager user
php generate_service_token.php manager@gmail.com managerpass "Manager Integration" "Manager level access"

# Staff user
php generate_service_token.php staff@gmail.com staffpass "Staff Integration" "Staff level access"
```

## 🚀 Quick Start for Your Other Laravel Project

Once you have your token, add it to your other Laravel project:

### 1. Add to `.env` file:
```env
APPOINTMENT_API_URL=https://migrationmanager.bansalcrm.com/api
APPOINTMENT_API_SERVICE_TOKEN=your_generated_token_here
```

### 2. Add to `config/services.php`:
```php
'appointment_api' => [
    'url' => env('APPOINTMENT_API_URL', 'https://migrationmanager.bansalcrm.com/api'),
    'service_token' => env('APPOINTMENT_API_SERVICE_TOKEN'),
    'timeout' => env('APPOINTMENT_API_TIMEOUT', 30),
],
```

### 3. Use in your controller:
```php
use App\Services\AppointmentApiService;

$apiService = new AppointmentApiService();
$appointments = $apiService->getAppointments();
```

## 🎉 You're Ready!

Now you can generate service tokens for any admin user and use them in your other Laravel projects without requiring login each time!

**Next Steps:**
1. Choose your preferred method to generate a token
2. Use the token in your other Laravel project
3. Start using the appointment API seamlessly 