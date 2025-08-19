# Fix for "Class Str not found" Error

## Problem
The ServiceAccountController is throwing a "Class Str not found" error when trying to generate service tokens. This is happening because the `Str` class is not properly imported.

## Root Cause
The error occurs in two files:
1. `app/Http/Controllers/API/ServiceAccountController.php` - line 44
2. `app/ServiceAccount.php` - line 47

Both files are using `\Str::random(64)` without properly importing the `Illuminate\Support\Str` class.

## Solution

### Step 1: Fix ServiceAccountController.php

**File:** `/home/bansalim/public_html/app/Http/Controllers/API/ServiceAccountController.php`

**Add this import at the top of the file (around line 6-10):**
```php
use Illuminate\Support\Str;
```

**Change line 44 from:**
```php
'token' => \Str::random(64),
```

**To:**
```php
'token' => Str::random(64),
```

### Step 2: Fix ServiceAccount.php

**File:** `/home/bansalim/public_html/app/ServiceAccount.php`

**Add this import at the top of the file (around line 6-8):**
```php
use Illuminate\Support\Str;
```

**Change line 47 from:**
```php
'token' => \Str::random(64),
```

**To:**
```php
'token' => Str::random(64),
```

## Alternative Solution (If imports don't work)

If the import approach doesn't work, use the fully qualified class name:

**In ServiceAccountController.php, line 44:**
```php
'token' => \Illuminate\Support\Str::random(64),
```

**In ServiceAccount.php, line 47:**
```php
'token' => \Illuminate\Support\Str::random(64),
```

## Files to Update

1. `/home/bansalim/public_html/app/Http/Controllers/API/ServiceAccountController.php`
2. `/home/bansalim/public_html/app/ServiceAccount.php`

## Testing

After applying the fix, test the API endpoint:

```bash
curl -X POST https://www.bansalimmigration.com.au/api/service-account/generate-token \
  -H "Content-Type: application/json" \
  -d '{
    "service_name": "Migration Manager CRM",
    "description": "Integration for migrationmanager.bansalcrm.com",
    "admin_email": "admin1@gmail.com",
    "admin_password": "123456"
  }'
```

## Expected Response

If the fix is successful, you should receive a response like:

```json
{
  "success": true,
  "data": {
    "service_account": {
      "id": 1,
      "service_name": "Migration Manager CRM",
      "token": "generated_token_here",
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  }
}
```

## Notes

- This is a one-time fix that needs to be applied on the live server
- The error occurs because Laravel's `Str` helper class needs to be properly imported
- After fixing, the service token generation should work correctly
- Make sure to backup the files before making changes 