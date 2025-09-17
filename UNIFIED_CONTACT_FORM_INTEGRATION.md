# Unified Contact Form – Integration Guide for Bansal Immigration Website

This guide explains the unified Contact Us form system that has been implemented in the Bansal Immigration website. It is framework-light (Blade + vanilla JS; no Vue/React) and supports AJAX, server-side validation, Google reCAPTCHA, database persistence, and email notification.

## Features
- Single POST endpoint with JSON responses for AJAX and redirect+flash for non-AJAX
- Server-side validation and Google reCAPTCHA verification
- Persists inquiries into `contacts` and `enquiries` tables
- Sends an email notification to the site's `MAIL_FROM_ADDRESS`
- Optional: form metadata (`form_source`, `form_variant`) for analytics
- Rate limiting and spam protection
- Honeypot field for additional spam protection

---

## Implementation Details

### Backend Components

#### 1. Controller
- **File**: `app/Http/Controllers/ContactController.php`
- **Route**: `POST /contact/submit` (named route: `contact.submit`)
- **Key responsibilities**:
  - Validate: `name`, `email`, `phone`, `subject`, `message`, `g-recaptcha-response` (+ optional `form_source`, `form_variant`)
  - Verify reCAPTCHA via `services.recaptcha.secret`
  - Create new `Contact` and `Enquiry` records
  - Send `App\Mail\ContactUsMail` to `config('mail.from.address')`
  - Return JSON on AJAX; redirect with flash otherwise
  - Rate limiting: max 5 attempts per minute per IP
  - Honeypot spam protection

#### 2. Models
- **Contact** (`app/Contact.php`):
  - Important columns: `name`, `contact_email`, `contact_phone`, `subject`, `message`
  - Workflow fields: `status`, `forwarded_to`, `forwarded_at`
  - Analytics fields: `form_source`, `form_variant`
  - Security field: `ip_address`
  - Accessor: `getEmailAttribute()` that maps to `contact_email`

- **Enquiry** (`app/Enquiry.php`):
  - Important columns: `first_name`, `email`, `phone`, `subject`, `message`
  - Security field: `ip_address`

#### 3. Database Migrations
- **Contacts table migration**: `database/migrations/2025_09_17_202429_add_unified_contact_form_fields_to_contacts_table.php`
- **Enquiries table migration**: `database/migrations/2025_09_17_202435_add_unified_contact_form_fields_to_enquiries_table.php`

New fields added:
```php
// Contacts table
$table->string('name')->nullable();
$table->string('subject')->nullable();
$table->text('message')->nullable();
$table->enum('status', ['unread', 'read', 'resolved', 'archived'])->default('unread');
$table->string('forwarded_to')->nullable();
$table->timestamp('forwarded_at')->nullable();
$table->string('form_source', 50)->nullable();
$table->string('form_variant', 50)->nullable();
$table->string('ip_address', 45)->nullable();

// Enquiries table
$table->string('subject')->nullable();
$table->string('ip_address', 45)->nullable();
```

#### 4. Mail Classes
- **ContactUsMail**: `app/Mail/ContactUsMail.php` (already exists)
- **ContactUsCustomerMail**: `app/Mail/ContactUsCustomerMail.php` (already exists, optional)

#### 5. Configuration
- **Services config**: `config/services.php`
```php
'recaptcha' => [
    'secret' => env('RECAPTCHA_SECRET'),
    'site_key' => env('RECAPTCHA_SITE_KEY'),
],
```

- **Environment variables needed**:
```env
MAIL_FROM_ADDRESS=info@bansalimmigration.com.au
RECAPTCHA_SECRET=your_recaptcha_secret_key
RECAPTCHA_SITE_KEY=your_recaptcha_site_key
```

---

## Frontend Components

### Unified Contact Form Component
**File**: `resources/views/components/unified-contact-form.blade.php`

This is a reusable Blade component that can be included anywhere in your website.

#### Usage Examples:

**Basic usage:**
```blade
@include('components.unified-contact-form')
```

**With custom parameters:**
```blade
@include('components.unified-contact-form', [
    'form_source' => 'footer',
    'form_variant' => 'compact',
    'show_phone' => true,
    'show_subject' => true
])
```

**Available parameters:**
- `form_source` (string): Identifies where the form is located (e.g., 'footer', 'contact-page', 'sidebar')
- `form_variant` (string): Identifies the form variant (e.g., 'compact', 'full', 'modal')
- `show_phone` (boolean): Whether to show the phone field (default: true)
- `show_subject` (boolean): Whether to show the subject field (default: true)
- `recaptcha_site_key` (string): reCAPTCHA site key (auto-loaded from config)

---

## Request/Response Contract

### Request Fields
- `name` (required): Full name
- `email` (required): Valid email address
- `phone` (optional): Phone number
- `subject` (required): Subject line
- `message` (required): Message content
- `g-recaptcha-response` (required): reCAPTCHA response token
- `form_source` (optional): Analytics - form location
- `form_variant` (optional): Analytics - form variant
- `website` (honeypot): Should be empty (spam protection)

### Success Response (200)
```json
{
    "success": true,
    "message": "Thank you! Your message has been sent successfully. We'll get back to you within 24 hours."
}
```

### Validation Error (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email must be a valid email address."],
        "name": ["Name is required."]
    }
}
```

### reCAPTCHA Error (422)
```json
{
    "success": false,
    "message": "reCAPTCHA validation failed",
    "errors": {
        "g-recaptcha-response": ["Please complete the reCAPTCHA verification."]
    }
}
```

### Rate Limit Error (422)
```json
{
    "success": false,
    "message": "Too many attempts. Please try again later.",
    "errors": {
        "rate_limit": ["Too many attempts. Please try again later."]
    }
}
```

### Server Error (500)
```json
{
    "success": false,
    "message": "Sorry, there was an error sending your message. Please try again."
}
```

---

## Setup Instructions

### 1. Run Database Migrations
```bash
php artisan migrate
```

### 2. Configure Environment Variables
Add to your `.env` file:
```env
# Mail Configuration (if not already set)
MAIL_FROM_ADDRESS=info@bansalimmigration.com.au

# Google reCAPTCHA (get from https://www.google.com/recaptcha)
RECAPTCHA_SECRET=your_secret_key_here
RECAPTCHA_SITE_KEY=your_site_key_here
```

### 3. Update Existing Contact Forms
Replace existing contact forms with the unified component:

**Before:**
```blade
<form action="{{ route('contact.emailsubmit') }}" method="post">
    <!-- old form fields -->
</form>
```

**After:**
```blade
@include('components.unified-contact-form', [
    'form_source' => 'contact-page',
    'form_variant' => 'full'
])
```

### 4. Add Required Meta Tag
Ensure your layout includes the CSRF meta tag:
```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

## Testing Checklist

- [ ] Form validation works (both client-side and server-side)
- [ ] reCAPTCHA verification works
- [ ] Records are created in both `contacts` and `enquiries` tables
- [ ] Email notifications are sent to admin
- [ ] AJAX submission works properly
- [ ] Non-AJAX fallback works (when JavaScript is disabled)
- [ ] Rate limiting prevents spam
- [ ] Honeypot field blocks bots
- [ ] Form works on mobile and desktop
- [ ] Form analytics data is captured (`form_source`, `form_variant`)

---

## Troubleshooting

### Common Issues

**1. CSRF Token Errors (401/419)**
- Ensure `@csrf` token is included in forms
- Add `<meta name="csrf-token" content="{{ csrf_token() }}">` to your layout
- Check that cookies are enabled

**2. Validation Errors (422)**
- Check required fields are filled
- Verify email format is valid
- Inspect the `errors` object in the JSON response

**3. reCAPTCHA Failures**
- Verify `RECAPTCHA_SECRET` and `RECAPTCHA_SITE_KEY` in `.env`
- Check domain settings in Google reCAPTCHA admin console
- Ensure reCAPTCHA script is loaded on the page

**4. Email Not Sending**
- Verify mail driver configuration (SMTP, etc.)
- Check `MAIL_FROM_ADDRESS` is set correctly
- Review Laravel logs for mail errors

**5. Database Errors**
- Run `php artisan migrate` to ensure all migrations are applied
- Check database connection settings
- Verify table structures match the migration files

---

## Advanced Features

### 1. Status Workflow
The `contacts` table includes a status field with workflow states:
- `unread` (default)
- `read`
- `resolved`
- `archived`

### 2. Forwarding System
Contacts can be forwarded to specific team members:
- `forwarded_to`: Email address of the assignee
- `forwarded_at`: Timestamp when forwarded

### 3. Analytics Integration
Track form performance using:
- `form_source`: Where the form is located
- `form_variant`: Which variant of the form was used

### 4. Customer Acknowledgment Emails
Uncomment the customer email section in `ContactController.php` to enable automatic acknowledgment emails to customers.

---

This unified contact form system provides a robust, scalable solution for handling contact inquiries across the Bansal Immigration website while maintaining compatibility with existing systems.
