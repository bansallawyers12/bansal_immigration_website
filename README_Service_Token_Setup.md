# Service Token Generation - Step 1

This document provides instructions for generating a service token to integrate the appointment API from `bansalimmigration.com.au` to `migrationmanager.bansalcrm.com`.

## Prerequisites

- Admin credentials for bansalimmigration.com.au
- cURL installed on your system
- PHP (for PHP script version)

## Step 1: Generate Service Token (One-time setup)

### Option 1: Using Bash Script (Linux/Mac)

1. Make the script executable:
   ```bash
   chmod +x generate_service_token.sh
   ```

2. Run the script:
   ```bash
   ./generate_service_token.sh
   ```

### Option 2: Using PHP Script (Windows/Linux/Mac)

1. Run the PHP script:
   ```bash
   php generate_service_token.php
   ```

### Option 3: Manual cURL Command

Run this command directly in your terminal:

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

On successful execution, you should receive a response like:

```json
{
  "success": true,
  "data": {
    "service_account": {
      "id": 1,
      "service_name": "Migration Manager CRM",
      "description": "Integration for migrationmanager.bansalcrm.com",
      "token": "your_generated_service_token_here",
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  }
}
```

## Next Steps

1. **Copy the generated token** from the response
2. **Add it to your .env file** on `migrationmanager.bansalcrm.com`:
   ```
   APPOINTMENT_API_SERVICE_TOKEN=your_generated_service_token_here
   ```
3. **Proceed to Step 2** of the integration process

## Troubleshooting

### Common Issues:

1. **Authentication Failed**: Verify your admin credentials
2. **Network Error**: Check your internet connection and API endpoint
3. **SSL Certificate Error**: The scripts disable SSL verification for development

### Error Response Example:
```json
{
  "success": false,
  "message": "Invalid credentials",
  "errors": {
    "admin_email": ["The provided email is not valid."],
    "admin_password": ["The provided password is incorrect."]
  }
}
```

## Security Notes

- Keep your service token secure and confidential
- Do not commit the token to version control
- Use environment variables to store the token
- The token is permanent and should be treated like a password

## Files Created

- `generate_service_token.php` - PHP version of the token generator
- `generate_service_token.sh` - Bash version of the token generator
- `README_Service_Token_Setup.md` - This documentation file 