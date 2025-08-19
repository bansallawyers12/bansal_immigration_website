<?php
/**
 * Website Integration for Appointment API
 * 
 * This file can be included in any website to display appointments from your API server.
 * Works with any PHP website, not just Laravel.
 */

class AppointmentWebsiteIntegration {
    private $apiUrl;
    private $token;
    private $adminEmail;
    private $adminPassword;
    
    public function __construct($apiUrl, $adminEmail, $adminPassword) {
        $this->apiUrl = rtrim($apiUrl, '/');
        $this->adminEmail = $adminEmail;
        $this->adminPassword = $adminPassword;
        $this->token = null;
    }
    
    /**
     * Login to API and get token
     */
    public function login() {
        $data = [
            'email' => $this->adminEmail,
            'password' => $this->adminPassword
        ];
        
        $response = $this->makeRequest('/login', 'POST', $data);
        
        if ($response && isset($response['success']) && $response['success']) {
            $this->token = $response['data']['token'];
            return true;
        }
        
        return false;
    }
    
    /**
     * Get appointments with filters
     */
    public function getAppointments($filters = []) {
        if (!$this->token) {
            if (!$this->login()) {
                return ['error' => 'Login failed'];
            }
        }
        
        $queryString = http_build_query($filters);
        $url = '/appointments' . ($queryString ? '?' . $queryString : '');
        
        $response = $this->makeRequest($url, 'GET', [], true);
        return $response;
    }
    
    /**
     * Get appointment statistics
     */
    public function getStatistics() {
        if (!$this->token) {
            if (!$this->login()) {
                return ['error' => 'Login failed'];
            }
        }
        
        $response = $this->makeRequest('/appointments/statistics/overview', 'GET', [], true);
        return $response;
    }
    
    /**
     * Create new appointment
     */
    public function createAppointment($data) {
        if (!$this->token) {
            if (!$this->login()) {
                return ['error' => 'Login failed'];
            }
        }
        
        $response = $this->makeRequest('/appointments', 'POST', $data, true);
        return $response;
    }
    
    /**
     * Update appointment
     */
    public function updateAppointment($id, $data) {
        if (!$this->token) {
            if (!$this->login()) {
                return ['error' => 'Login failed'];
            }
        }
        
        $response = $this->makeRequest('/appointments/' . $id, 'PUT', $data, true);
        return $response;
    }
    
    /**
     * Delete appointment
     */
    public function deleteAppointment($id) {
        if (!$this->token) {
            if (!$this->login()) {
                return ['error' => 'Login failed'];
            }
        }
        
        $response = $this->makeRequest('/appointments/' . $id, 'DELETE', [], true);
        return $response;
    }
    
    /**
     * Make HTTP request to API
     */
    private function makeRequest($endpoint, $method, $data = [], $useToken = false) {
        $url = $this->apiUrl . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        if ($useToken && $this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($response === false) {
            return ['error' => 'Request failed'];
        }
        
        $decoded = json_decode($response, true);
        return $decoded ?: ['error' => 'Invalid JSON response'];
    }
}

/**
 * HTML Helper Functions
 */
class AppointmentHTMLHelper {
    
    /**
     * Display appointments table
     */
    public static function displayAppointmentsTable($appointments) {
        if (!isset($appointments['data']['data']) || empty($appointments['data']['data'])) {
            return '<div class="alert alert-info">No appointments found.</div>';
        }
        
        $html = '<div class="table-responsive">';
        $html .= '<table class="table table-striped table-hover">';
        $html .= '<thead class="table-dark">';
        $html .= '<tr>';
        $html .= '<th>#</th>';
        $html .= '<th>Client</th>';
        $html .= '<th>DateTime</th>';
        $html .= '<th>Nature of Enquiry</th>';
        $html .= '<th>Description</th>';
        $html .= '<th>Added By</th>';
        $html .= '<th>Status</th>';
        $html .= '<th>Action</th>';
        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';
        
        foreach ($appointments['data']['data'] as $appointment) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($appointment['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($appointment['full_name'] ?? 'N/A') . '</td>';
            $html .= '<td>' . htmlspecialchars($appointment['date'] . ' ' . $appointment['time']) . '</td>';
            $html .= '<td>' . htmlspecialchars($appointment['nature_of_enquiry'] ?? 'N/A') . '</td>';
            $html .= '<td>' . htmlspecialchars(substr($appointment['description'] ?? '', 0, 50)) . '...</td>';
            $html .= '<td>' . htmlspecialchars($appointment['user']['name'] ?? 'N/A') . '</td>';
            $html .= '<td><span class="badge bg-' . self::getStatusColor($appointment['status']) . '">' . htmlspecialchars(ucfirst($appointment['status'])) . '</span></td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-sm btn-primary" onclick="viewAppointment(' . $appointment['id'] . ')">View</button> ';
            $html .= '<button class="btn btn-sm btn-warning" onclick="editAppointment(' . $appointment['id'] . ')">Edit</button> ';
            $html .= '<button class="btn btn-sm btn-danger" onclick="deleteAppointment(' . $appointment['id'] . ')">Delete</button>';
            $html .= '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Display statistics cards
     */
    public static function displayStatistics($statistics) {
        if (!isset($statistics['data'])) {
            return '<div class="alert alert-warning">Unable to load statistics.</div>';
        }
        
        $stats = $statistics['data'];
        
        $html = '<div class="row">';
        $html .= '<div class="col-md-3 mb-3">';
        $html .= '<div class="card bg-primary text-white">';
        $html .= '<div class="card-body">';
        $html .= '<h5 class="card-title">Total</h5>';
        $html .= '<h2>' . $stats['total'] . '</h2>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-md-3 mb-3">';
        $html .= '<div class="card bg-warning text-white">';
        $html .= '<div class="card-body">';
        $html .= '<h5 class="card-title">Pending</h5>';
        $html .= '<h2>' . $stats['pending'] . '</h2>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-md-3 mb-3">';
        $html .= '<div class="card bg-success text-white">';
        $html .= '<div class="card-body">';
        $html .= '<h5 class="card-title">Confirmed</h5>';
        $html .= '<h2>' . $stats['confirmed'] . '</h2>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        $html .= '<div class="col-md-3 mb-3">';
        $html .= '<div class="card bg-info text-white">';
        $html .= '<div class="card-body">';
        $html .= '<h5 class="card-title">Completed</h5>';
        $html .= '<h2>' . $stats['completed'] . '</h2>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Get status color for badges
     */
    private static function getStatusColor($status) {
        switch (strtolower($status)) {
            case 'pending': return 'warning';
            case 'confirmed': return 'success';
            case 'completed': return 'info';
            case 'cancelled': return 'danger';
            case 'rescheduled': return 'secondary';
            default: return 'primary';
        }
    }
    
    /**
     * Display search form
     */
    public static function displaySearchForm() {
        $html = '<div class="card mb-4">';
        $html .= '<div class="card-header bg-primary text-white">';
        $html .= '<h5 class="mb-0">Search Appointments</h5>';
        $html .= '</div>';
        $html .= '<div class="card-body">';
        $html .= '<form id="searchForm" method="GET">';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-3">';
        $html .= '<label for="date_search" class="form-label">Appointment Date</label>';
        $html .= '<input type="date" class="form-control" id="date_search" name="date_search">';
        $html .= '</div>';
        $html .= '<div class="col-md-3">';
        $html .= '<label for="search" class="form-label">Client/Description</label>';
        $html .= '<input type="text" class="form-control" id="search" name="search" placeholder="Search with Client reference, description">';
        $html .= '</div>';
        $html .= '<div class="col-md-2">';
        $html .= '<label for="status" class="form-label">Status</label>';
        $html .= '<select class="form-control" id="status" name="status">';
        $html .= '<option value="">All Status</option>';
        $html .= '<option value="pending">Pending</option>';
        $html .= '<option value="confirmed">Confirmed</option>';
        $html .= '<option value="completed">Completed</option>';
        $html .= '<option value="cancelled">Cancelled</option>';
        $html .= '<option value="rescheduled">Rescheduled</option>';
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<div class="col-md-2">';
        $html .= '<label for="per_page" class="form-label">Per Page</label>';
        $html .= '<select class="form-control" id="per_page" name="per_page">';
        $html .= '<option value="10">10</option>';
        $html .= '<option value="25">25</option>';
        $html .= '<option value="50">50</option>';
        $html .= '<option value="100">100</option>';
        $html .= '</select>';
        $html .= '</div>';
        $html .= '<div class="col-md-2">';
        $html .= '<label class="form-label">&nbsp;</label>';
        $html .= '<button type="submit" class="btn btn-primary d-block w-100">Search</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</form>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}

/**
 * JavaScript Helper Functions
 */
class AppointmentJavaScriptHelper {
    
    /**
     * Generate JavaScript for appointment management
     */
    public static function generateJavaScript() {
        $js = '<script>
        // Appointment management functions
        function viewAppointment(id) {
            // Implement view appointment modal
            alert("View appointment " + id);
        }
        
        function editAppointment(id) {
            // Implement edit appointment modal
            alert("Edit appointment " + id);
        }
        
        function deleteAppointment(id) {
            if (confirm("Are you sure you want to delete this appointment?")) {
                // Implement delete appointment
                alert("Delete appointment " + id);
            }
        }
        
        // Auto-refresh appointments every 5 minutes
        setInterval(function() {
            location.reload();
        }, 300000);
        
        // Initialize search form
        document.addEventListener("DOMContentLoaded", function() {
            // Set current values from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            document.getElementById("date_search").value = urlParams.get("date_search") || "";
            document.getElementById("search").value = urlParams.get("search") || "";
            document.getElementById("status").value = urlParams.get("status") || "";
            document.getElementById("per_page").value = urlParams.get("per_page") || "10";
        });
        </script>';
        
        return $js;
    }
}
?> 