@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
<div class="container-fluid">
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
            <a href="{{ route('appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                New Appointment
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if(isset($statistics['data']))
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total</h5>
                    <h2>{{ $statistics['data']['total'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2>{{ $statistics['data']['pending'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Confirmed</h5>
                    <h2>{{ $statistics['data']['confirmed'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <h2>{{ $statistics['data']['completed'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Search Form -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Search Appointments</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('appointments.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="date_search" class="form-label">Appointment Date</label>
                        <input type="date" class="form-control" id="date_search" name="date_search" value="{{ $dateSearch ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Client/Description</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ $search ?? '' }}" placeholder="Search with Client reference, description">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ ($status ?? '') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ ($status ?? '') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            <option value="rescheduled" {{ ($status ?? '') == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="per_page" class="form-label">Per Page</label>
                        <select class="form-control" id="per_page" name="per_page">
                            <option value="10" {{ ($perPage ?? 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ ($perPage ?? 10) == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ ($perPage ?? 10) == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ ($perPage ?? 10) == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block w-100">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

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
                    @if(isset($appointments['data']['total']))
                        <span class="badge bg-secondary">
                            Total: {{ $appointments['data']['total'] }} appointments
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if(isset($appointments['data']['data']) && count($appointments['data']['data']) > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Client</th>
                                <th>DateTime</th>
                                <th>Nature of Enquiry</th>
                                <th>Description</th>
                                <th>Added By</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments['data']['data'] as $appointment)
                            <tr>
                                <td>{{ $appointment['id'] }}</td>
                                <td>{{ $appointment['full_name'] ?? 'N/A' }}</td>
                                <td>{{ $appointment['date'] }} {{ $appointment['time'] }}</td>
                                <td>{{ $appointment['nature_of_enquiry'] ?? 'N/A' }}</td>
                                <td>{{ Str::limit($appointment['description'] ?? '', 50) }}</td>
                                <td>{{ $appointment['user']['name'] ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ getStatusColor($appointment['status']) }}">
                                        {{ ucfirst($appointment['status']) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('appointments.show', $appointment['id']) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('appointments.edit', $appointment['id']) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('appointments.destroy', $appointment['id']) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-4 text-center">
                    <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">No appointments found</h5>
                    <p class="text-muted">Try adjusting your search criteria or create a new appointment.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Pagination -->
    @if(isset($appointments['data']['links']))
    <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Appointments pagination">
            <ul class="pagination">
                @foreach($appointments['data']['links'] as $link)
                    <li class="page-item {{ $link['active'] ? 'active' : '' }}">
                        <a class="page-link" href="{{ $link['url'] }}">
                            {!! $link['label'] !!}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
    @endif
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Actions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bulkUpdateForm" action="{{ route('appointments.bulk-update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="bulk_status" class="form-label">Update Status</label>
                        <select class="form-control" id="bulk_status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="rescheduled">Rescheduled</option>
                        </select>
                    </div>
                    <input type="hidden" id="selected_appointments" name="appointment_ids">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="bulkUpdateForm" class="btn btn-primary">Update Selected</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-refresh every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);

// Bulk actions
function selectAll() {
    const checkboxes = document.querySelectorAll('.appointment-checkbox');
    const selectAllCheckbox = document.getElementById('select-all');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    updateBulkActions();
}

function updateBulkActions() {
    const checkboxes = document.querySelectorAll('.appointment-checkbox:checked');
    const bulkActionsBtn = document.getElementById('bulk-actions-btn');
    
    if (checkboxes.length > 0) {
        bulkActionsBtn.disabled = false;
        bulkActionsBtn.textContent = `Bulk Actions (${checkboxes.length})`;
    } else {
        bulkActionsBtn.disabled = true;
        bulkActionsBtn.textContent = 'Bulk Actions';
    }
}

function openBulkActionsModal() {
    const checkboxes = document.querySelectorAll('.appointment-checkbox:checked');
    const appointmentIds = Array.from(checkboxes).map(cb => cb.value);
    
    document.getElementById('selected_appointments').value = JSON.stringify(appointmentIds);
    
    const modal = new bootstrap.Modal(document.getElementById('bulkActionsModal'));
    modal.show();
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners for checkboxes
    document.querySelectorAll('.appointment-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', selectAll);
    }
});
</script>
@endpush

@php
function getStatusColor($status) {
    switch (strtolower($status)) {
        case 'pending': return 'warning';
        case 'confirmed': return 'success';
        case 'completed': return 'info';
        case 'cancelled': return 'danger';
        case 'rescheduled': return 'secondary';
        default: return 'primary';
    }
}
@endphp 