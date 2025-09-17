<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Contact Form Test - Bansal Immigration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .test-section {
            margin-bottom: 3rem;
            padding: 2rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
        }
        .config-check {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="text-center mb-5">
                    <h1 class="display-4 text-primary">
                        <i class="fas fa-vial me-3"></i>
                        Contact System Test Page
                    </h1>
                    <p class="lead text-muted">Test the unified contact form and admin management system</p>
                </div>

                <!-- System Status -->
                <div class="test-section">
                    <h3 class="mb-4">
                        <i class="fas fa-heartbeat text-success me-2"></i>
                        System Status
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="config-check">
                                <h6>Configuration Status</h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <span class="badge bg-{{ config('services.recaptcha.secret') ? 'success' : 'warning' }} status-badge">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            reCAPTCHA: {{ config('services.recaptcha.secret') ? 'Configured' : 'Not Configured' }}
                                        </span>
                                    </li>
                                    <li class="mb-2">
                                        <span class="badge bg-{{ config('mail.from.address') ? 'success' : 'warning' }} status-badge">
                                            <i class="fas fa-envelope me-1"></i>
                                            Mail: {{ config('mail.from.address') ?: 'Not Configured' }}
                                        </span>
                                    </li>
                                    <li class="mb-2">
                                        <span class="badge bg-success status-badge">
                                            <i class="fas fa-database me-1"></i>
                                            Database: Connected
                                        </span>
                                    </li>
                                    <li>
                                        <span class="badge bg-success status-badge">
                                            <i class="fas fa-route me-1"></i>
                                            Routes: Active
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="config-check">
                                <h6>Quick Admin Links</h6>
                                <div class="d-grid gap-2">
                                    <a href="/admin/contact-management" target="_blank" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-list me-1"></i> View All Queries
                                    </a>
                                    <a href="/admin/contact-management/dashboard" target="_blank" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-chart-bar me-1"></i> Admin Dashboard
                                    </a>
                                    <a href="/admin/login" target="_blank" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-sign-in-alt me-1"></i> Admin Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Test Instructions -->
                <div class="test-section">
                    <h3 class="mb-4">
                        <i class="fas fa-clipboard-list text-info me-2"></i>
                        Testing Instructions
                    </h3>
                    <div class="alert alert-info">
                        <h6 class="alert-heading">How to Test the System:</h6>
                        <ol class="mb-0">
                            <li><strong>Form Validation:</strong> Try submitting forms with empty required fields</li>
                            <li><strong>Email Validation:</strong> Test with invalid email formats</li>
                            <li><strong>reCAPTCHA:</strong> Verify the reCAPTCHA challenge appears and works</li>
                            <li><strong>AJAX Submission:</strong> Forms should submit without page reload</li>
                            <li><strong>Rate Limiting:</strong> Try submitting the same form 6+ times quickly</li>
                            <li><strong>Admin Panel:</strong> Check that submissions appear in the admin dashboard</li>
                            <li><strong>Email Notifications:</strong> Verify admin receives email notifications</li>
                            <li><strong>Mobile Testing:</strong> Test forms on different screen sizes</li>
                        </ol>
                    </div>
                </div>

                <!-- Standard Contact Form Test -->
                <div class="test-section">
                    <h3 class="mb-4">
                        <i class="fas fa-form text-primary me-2"></i>
                        Standard Contact Form (Full Features)
                    </h3>
                    <p class="text-muted mb-4">This form includes all fields: name, email, phone, subject, and message.</p>
                    
                    @include('components.unified-contact-form', [
                        'form_source' => 'test-page',
                        'form_variant' => 'full-test',
                        'show_phone' => true,
                        'show_subject' => true
                    ])
                </div>

                <!-- Compact Form Test -->
                <div class="test-section">
                    <h3 class="mb-4">
                        <i class="fas fa-compress-alt text-success me-2"></i>
                        Compact Form (No Phone Field)
                    </h3>
                    <p class="text-muted mb-4">This form excludes the phone field for simpler layouts.</p>
                    
                    @include('components.unified-contact-form', [
                        'form_source' => 'test-page',
                        'form_variant' => 'compact-test',
                        'show_phone' => false,
                        'show_subject' => true
                    ])
                </div>

                <!-- Minimal Form Test -->
                <div class="test-section">
                    <h3 class="mb-4">
                        <i class="fas fa-minus-circle text-warning me-2"></i>
                        Minimal Form (Name, Email, Message Only)
                    </h3>
                    <p class="text-muted mb-4">This form only includes essential fields for minimal designs.</p>
                    
                    @include('components.unified-contact-form', [
                        'form_source' => 'test-page',
                        'form_variant' => 'minimal-test',
                        'show_phone' => false,
                        'show_subject' => false
                    ])
                </div>

                <!-- Testing Results -->
                <div class="test-section">
                    <h3 class="mb-4">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Test Results & Statistics
                    </h3>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h4 class="text-primary">{{ \App\Contact::count() }}</h4>
                                    <p class="card-text">Total Queries</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h4 class="text-warning">{{ \App\Contact::where('status', 'unread')->count() }}</h4>
                                    <p class="card-text">Unread Queries</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h4 class="text-info">{{ \App\Contact::whereDate('created_at', today())->count() }}</h4>
                                    <p class="card-text">Today's Queries</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(\App\Contact::where('form_source', 'test-page')->count() > 0)
                    <div class="alert alert-success mt-4">
                        <h6 class="alert-heading">✅ Test Submissions Detected!</h6>
                        <p class="mb-0">
                            Found {{ \App\Contact::where('form_source', 'test-page')->count() }} test submissions from this page.
                            <a href="/admin/contact-management?form_source=test-page" target="_blank" class="alert-link">View in Admin Panel</a>
                        </p>
                    </div>
                    @endif
                </div>

                <!-- Debug Information -->
                <div class="test-section">
                    <h3 class="mb-4">
                        <i class="fas fa-bug text-secondary me-2"></i>
                        Debug Information
                    </h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Environment</h6>
                            <ul class="list-unstyled small text-muted">
                                <li><strong>App Environment:</strong> {{ app()->environment() }}</li>
                                <li><strong>Debug Mode:</strong> {{ config('app.debug') ? 'Enabled' : 'Disabled' }}</li>
                                <li><strong>Laravel Version:</strong> {{ app()->version() }}</li>
                                <li><strong>PHP Version:</strong> {{ PHP_VERSION }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Form Configuration</h6>
                            <ul class="list-unstyled small text-muted">
                                <li><strong>Contact Route:</strong> {{ route('contact.submit') }}</li>
                                <li><strong>CSRF Token:</strong> {{ substr(csrf_token(), 0, 10) }}...</li>
                                <li><strong>reCAPTCHA Site Key:</strong> {{ config('services.recaptcha.site_key') ? 'Set' : 'Not Set' }}</li>
                                <li><strong>Mail From:</strong> {{ config('mail.from.address') ?: 'Not Set' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-center mt-5">
                    <p class="text-muted">
                        <i class="fas fa-clock me-1"></i>
                        Test page generated on {{ date('F j, Y \a\t g:i A') }}
                    </p>
                    <p class="small text-muted">
                        For technical support, check the 
                        <a href="#" onclick="alert('Check COMPLETE_CONTACT_SYSTEM_GUIDE.md for troubleshooting')">documentation</a> 
                        or review Laravel logs.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Test Page Enhancements -->
    <script>
        // Add some test page specific functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Highlight successful form submissions
            document.addEventListener('contactFormSuccess', function(e) {
                const testSection = e.target.closest('.test-section');
                if (testSection) {
                    testSection.style.border = '2px solid #28a745';
                    testSection.style.backgroundColor = '#f8fff9';
                    
                    setTimeout(() => {
                        testSection.style.border = '';
                        testSection.style.backgroundColor = '';
                    }, 3000);
                }
            });
            
            // Add form submission counter
            let submissionCount = 0;
            document.addEventListener('contactFormSubmit', function() {
                submissionCount++;
                console.log(`Form submission attempt #${submissionCount}`);
            });
        });
    </script>
</body>
</html>
