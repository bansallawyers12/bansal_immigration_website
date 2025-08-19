<?php
/**
 * Complete Appointments Page Example
 * 
 * This is a complete example page that you can use on your website to display appointments.
 * Just include this file in your website and it will show all appointments from your API.
 */

// Include the integration file
require_once 'website_integration.php';

// Configuration - Update these with your actual API details
$apiUrl = 'http://localhost/bansalimmigration.com.au/api'; // Your API server URL
$adminEmail = 'ankitbansal0011@gmail.au'; // Admin email
$adminPassword = 'your_password_here'; // Admin password

// Initialize the integration
$appointmentAPI = new AppointmentWebsiteIntegration($apiUrl, $adminEmail, $adminPassword);

// Get search parameters
$search = $_GET['search'] ?? '';
$dateSearch = $_GET['date_search'] ?? '';
$status = $_GET['status'] ?? '';
$perPage = $_GET['per_page'] ?? 10;

// Build filters
$filters = [];
if ($search) $filters['search'] = $search;
if ($dateSearch) $filters['date_from'] = $dateSearch;
if ($status) $filters['status'] = $status;
if ($perPage) $filters['per_page'] = $perPage;

// Get appointments and statistics
$appointments = $appointmentAPI->getAppointments($filters);
$statistics = $appointmentAPI->getStatistics();

// Check for errors
$error = null;
if (isset($appointments['error'])) {
    $error = $appointments['error'];
}
if (isset($statistics['error'])) {
    $error = $statistics['error'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - Bansal Immigration</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        .table th {
            background-color: #343a40;
            color: white;
            border-color: #454d55;
        }
        .badge {
            font-size: 0.75em;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .stats-card {
            transition: transform 0.2s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .search-form {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .table-responsive {
            border-radius: 0.375rem;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-calendar-alt me-2"></i>
                Bansal Immigration - Appointments
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text">
                    <i class="fas fa-clock me-1"></i>
                    <?php echo date('d M Y, h:i A'); ?>
                </span>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Error Display -->
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error:</strong> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h1 class="h3 mb-0">
                    <i class="fas fa-calendar-check me-2 text-primary"></i>
                    Appointments Management
                </h1>
                <p class="text-muted">Manage and view all appointments from your CRM system</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-success" onclick="location.reload()">
                    <i class="fas fa-sync-alt me-1"></i>
                    Refresh
                </button>
                <button class="btn btn-primary" onclick="addNewAppointment()">
                    <i class="fas fa-plus me-1"></i>
                    New Appointment
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <?php if (!$error): ?>
            <div class="mb-4">
                <?php echo AppointmentHTMLHelper::displayStatistics($statistics); ?>
            </div>
        <?php endif; ?>

        <!-- Search Form -->
        <?php echo AppointmentHTMLHelper::displaySearchForm(); ?>

        <!-- Appointments Table -->
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Appointments List
                        </h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <?php if (isset($appointments['data']['total'])): ?>
                            <span class="badge bg-secondary">
                                Total: <?php echo $appointments['data']['total']; ?> appointments
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (!$error): ?>
                    <?php echo AppointmentHTMLHelper::displayAppointmentsTable($appointments); ?>
                <?php else: ?>
                    <div class="p-4 text-center">
                        <i class="fas fa-exclamation-circle text-warning" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Unable to load appointments</h5>
                        <p class="text-muted">Please check your API connection and try again.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Pagination -->
        <?php if (!$error && isset($appointments['data']['links'])): ?>
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Appointments pagination">
                    <ul class="pagination">
                        <?php foreach ($appointments['data']['links'] as $link): ?>
                            <li class="page-item <?php echo $link['active'] ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo $link['url']; ?>">
                                    <?php echo $link['label']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-light mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        <i class="fas fa-shield-alt me-1"></i>
                        Secure API Integration
                    </p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="mb-0 text-muted">
                        Last updated: <?php echo date('d M Y, h:i:s A'); ?>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <?php echo AppointmentJavaScriptHelper::generateJavaScript(); ?>
    
    <script>
        // Additional JavaScript functions
        function addNewAppointment() {
            // Implement add new appointment modal
            alert('Add new appointment functionality - implement your modal here');
        }
        
        // Enhanced view appointment function
        function viewAppointment(id) {
            // You can implement a modal here to show appointment details
            const modal = `
                <div class="modal fade" id="viewModal" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Appointment Details #${id}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p>Loading appointment details...</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            const existingModal = document.getElementById('viewModal');
            if (existingModal) {
                existingModal.remove();
            }
            
            // Add modal to body
            document.body.insertAdjacentHTML('beforeend', modal);
            
            // Show modal
            const modalElement = new bootstrap.Modal(document.getElementById('viewModal'));
            modalElement.show();
        }
        
        // Enhanced edit appointment function
        function editAppointment(id) {
            // Implement edit appointment modal
            alert(`Edit appointment ${id} - implement your edit modal here`);
        }
        
        // Enhanced delete appointment function
        function deleteAppointment(id) {
            if (confirm('Are you sure you want to delete this appointment? This action cannot be undone.')) {
                // Implement delete functionality
                alert(`Delete appointment ${id} - implement your delete functionality here`);
            }
        }
        
        // Auto-refresh every 5 minutes
        setInterval(function() {
            // Show refresh indicator
            const refreshBtn = document.querySelector('.btn-success');
            if (refreshBtn) {
                refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Refreshing...';
                refreshBtn.disabled = true;
            }
            
            // Reload page
            setTimeout(() => {
                location.reload();
            }, 1000);
        }, 300000); // 5 minutes
        
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Set current values from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            document.getElementById("date_search").value = urlParams.get("date_search") || "";
            document.getElementById("search").value = urlParams.get("search") || "";
            document.getElementById("status").value = urlParams.get("status") || "";
            document.getElementById("per_page").value = urlParams.get("per_page") || "10";
        });
    </script>
</body>
</html> 