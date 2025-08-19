#!/bin/bash

# Service Token Generator for Appointment API Integration
# 
# This script generates a service token for integrating with the appointment API
# from bansalimmigration.com.au to migrationmanager.bansalcrm.com

echo "=== Step 1: Generate Service Token (One-time setup) ==="
echo "Generating permanent service token using admin credentials..."
echo ""

# Configuration
URL="https://www.bansalimmigration.com.au/api/service-account/generate-token"
SERVICE_NAME="Migration Manager CRM"
DESCRIPTION="Integration for migrationmanager.bansalcrm.com"
ADMIN_EMAIL="admin@gmail.com"
ADMIN_PASSWORD="bansal"

# Execute the curl command
echo "Making API request to: $URL"
echo "Service Name: $SERVICE_NAME"
echo "Description: $DESCRIPTION"
echo "Admin Email: $ADMIN_EMAIL"
echo ""

response=$(curl -s -w "\n%{http_code}" -X POST "$URL" \
  -H "Content-Type: application/json" \
  -d "{
    \"service_name\": \"$SERVICE_NAME\",
    \"description\": \"$DESCRIPTION\",
    \"admin_email\": \"$ADMIN_EMAIL\",
    \"admin_password\": \"$ADMIN_PASSWORD\"
  }")

# Extract HTTP status code (last line)
http_code=$(echo "$response" | tail -n1)
# Extract response body (all lines except last)
response_body=$(echo "$response" | head -n -1)

echo "=== Service Token Generation Result ==="
echo "HTTP Code: $http_code"
echo "Response: $response_body"
echo ""

# Parse JSON response (requires jq to be installed)
if command -v jq &> /dev/null; then
    success=$(echo "$response_body" | jq -r '.success // false')
    token=$(echo "$response_body" | jq -r '.data.service_account.token // empty')
    service_id=$(echo "$response_body" | jq -r '.data.service_account.id // empty')
    service_name=$(echo "$response_body" | jq -r '.data.service_account.service_name // empty')
    created_at=$(echo "$response_body" | jq -r '.data.service_account.created_at // empty')
    message=$(echo "$response_body" | jq -r '.message // empty')
    errors=$(echo "$response_body" | jq -r '.errors // empty')
else
    # Fallback parsing without jq
    success=$(echo "$response_body" | grep -o '"success":[^,]*' | cut -d':' -f2 | tr -d ' ')
    token=$(echo "$response_body" | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
    message=$(echo "$response_body" | grep -o '"message":"[^"]*"' | cut -d'"' -f4)
fi

if [ "$http_code" = "200" ] && [ "$success" = "true" ] && [ -n "$token" ]; then
    echo "✅ SUCCESS! Service token generated successfully."
    echo ""
    echo "🔑 SERVICE TOKEN:"
    echo "$token"
    echo ""
    echo "📝 Add this to your migrationmanager.bansalcrm.com .env file:"
    echo "APPOINTMENT_API_SERVICE_TOKEN=$token"
    echo ""
    
    if [ -n "$service_id" ]; then
        echo "📋 Service Account Details:"
        echo "ID: $service_id"
        echo "Name: $service_name"
        echo "Created: $created_at"
    fi
else
    echo "❌ ERROR: Failed to generate service token."
    if [ -n "$message" ]; then
        echo "Message: $message"
    fi
    if [ -n "$errors" ]; then
        echo "Errors: $errors"
    fi
fi

echo ""
echo "=== End ===" 