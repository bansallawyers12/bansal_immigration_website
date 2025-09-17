@extends('layouts.adminnew')

@section('title', 'Contact Queries Management')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contact Queries</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Contact Queries</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total'] }}</h3>
                            <p>Total Queries</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['unread'] }}</h3>
                            <p>Unread</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-envelope-open"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['resolved'] }}</h3>
                            <p>Resolved</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $stats['today'] }}</h3>
                            <p>Today</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $stats['this_week'] }}</h3>
                            <p>This Week</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-week"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <h3>{{ $stats['this_month'] }}</h3>
                            <p>This Month</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filters & Search</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.contact-management.index') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Search</label>
                                    <input type="text" name="search" class="form-control" placeholder="Name, email, subject..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="">All Status</option>
                                        <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                                        <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Form Source</label>
                                    <select name="form_source" class="form-control">
                                        <option value="">All Sources</option>
                                        @foreach($formSources as $source)
                                            <option value="{{ $source }}" {{ request('form_source') == $source ? 'selected' : '' }}>
                                                {{ ucfirst($source) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date From</label>
                                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Date To</label>
                                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Queries Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Contact Queries ({{ $contacts->total() }} total)</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportQueries()">
                                <i class="fas fa-download"></i> Export CSV
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="showBulkActions()">
                                <i class="fas fa-tasks"></i> Bulk Actions
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    
                    <!-- Bulk Actions Bar (Initially Hidden) -->
                    <div id="bulkActionsBar" class="bg-light p-3 border-bottom" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <span id="selectedCount">0</span> queries selected
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="bulkAction('mark_read')">
                                        Mark as Read
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-success" onclick="bulkAction('mark_resolved')">
                                        Mark as Resolved
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="bulkAction('mark_archived')">
                                        Archive
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="bulkAction('delete')">
                                        Delete
                                    </button>
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="hideBulkActions()">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($contacts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" id="selectAll" class="bulk-checkbox-main">
                                    </th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Source</th>
                                    <th>Date</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($contacts as $contact)
                                <tr class="{{ $contact->status === 'unread' ? 'font-weight-bold' : '' }}">
                                    <td>
                                        <input type="checkbox" class="bulk-checkbox" value="{{ $contact->id }}">
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.contact-management.show', $contact->id) }}" class="text-decoration-none">
                                            {{ $contact->name ?: 'N/A' }}
                                        </a>
                                        @if($contact->status === 'unread')
                                            <span class="badge badge-warning badge-sm ml-1">New</span>
                                        @endif
                                    </td>
                                    <td>{{ $contact->contact_email }}</td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $contact->subject }}">
                                            {{ $contact->subject ?: 'No subject' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $contact->status === 'unread' ? 'warning' : ($contact->status === 'resolved' ? 'success' : ($contact->status === 'archived' ? 'secondary' : 'info')) }}">
                                            {{ ucfirst($contact->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($contact->form_source)
                                            <small class="text-muted">{{ ucfirst($contact->form_source) }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $contact->created_at->format('M d, Y H:i') }}</small>
                                        @if($contact->forwarded_to)
                                            <br><small class="text-success">
                                                <i class="fas fa-share"></i> Forwarded
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.contact-management.show', $contact->id) }}" class="btn btn-sm btn-outline-primary" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-info" onclick="showForwardModal({{ $contact->id }})" title="Forward">
                                                <i class="fas fa-share"></i>
                                            </button>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="#" onclick="updateStatus({{ $contact->id }}, 'read')">
                                                        <i class="fas fa-eye"></i> Mark as Read
                                                    </a>
                                                    <a class="dropdown-item" href="#" onclick="updateStatus({{ $contact->id }}, 'resolved')">
                                                        <i class="fas fa-check"></i> Mark as Resolved
                                                    </a>
                                                    <a class="dropdown-item" href="#" onclick="updateStatus({{ $contact->id }}, 'archived')">
                                                        <i class="fas fa-archive"></i> Archive
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#" onclick="deleteQuery({{ $contact->id }})">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="card-footer">
                        {{ $contacts->links() }}
                    </div>
                    
                    @else
                    <div class="text-center py-4">
                        <p class="text-muted">No contact queries found matching your criteria.</p>
                        @if(request()->hasAny(['search', 'status', 'date_from', 'date_to', 'form_source']))
                            <a href="{{ route('admin.contact-management.index') }}" class="btn btn-sm btn-outline-primary">Clear Filters</a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Forward Modal -->
<div class="modal fade" id="forwardModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Forward Query</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="forwardForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Forward to Email <span class="text-danger">*</span></label>
                        <input type="email" name="forward_to" class="form-control" placeholder="recipient@example.com" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="form-group">
                        <label>Additional Message (Optional)</label>
                        <textarea name="forward_message" class="form-control" rows="3" placeholder="Add a note for the recipient..."></textarea>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="include_original" class="form-check-input" id="includeOriginal" checked>
                        <label class="form-check-label" for="includeOriginal">
                            Include original query details
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span class="forward-text">Forward Query</span>
                        <span class="forward-loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Forwarding...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentForwardId = null;

// Forward Modal
function showForwardModal(contactId) {
    currentForwardId = contactId;
    $('#forwardModal').modal('show');
    $('#forwardForm')[0].reset();
    $('#forwardForm .is-invalid').removeClass('is-invalid');
    $('#forwardForm .invalid-feedback').text('');
}

// Forward Form Submit
$('#forwardForm').on('submit', function(e) {
    e.preventDefault();
    
    if (!currentForwardId) return;
    
    const form = $(this);
    const submitBtn = form.find('button[type="submit"]');
    const submitText = submitBtn.find('.forward-text');
    const loadingText = submitBtn.find('.forward-loading');
    
    // Clear previous errors
    form.find('.is-invalid').removeClass('is-invalid');
    form.find('.invalid-feedback').text('');
    
    // Set loading state
    submitBtn.prop('disabled', true);
    submitText.hide();
    loadingText.show();
    
    $.ajax({
        url: `/admin/contact-management/${currentForwardId}/forward`,
        method: 'POST',
        data: form.serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                $('#forwardModal').modal('hide');
                showAlert('success', response.message);
                setTimeout(() => location.reload(), 1000);
            }
        },
        error: function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                Object.keys(errors).forEach(field => {
                    const input = form.find(`[name="${field}"]`);
                    input.addClass('is-invalid');
                    input.siblings('.invalid-feedback').text(errors[field][0]);
                });
            } else {
                showAlert('danger', 'Failed to forward query. Please try again.');
            }
        },
        complete: function() {
            submitBtn.prop('disabled', false);
            submitText.show();
            loadingText.hide();
        }
    });
});

// Update Status
function updateStatus(contactId, status) {
    if (!confirm(`Are you sure you want to mark this query as ${status}?`)) return;
    
    $.ajax({
        url: `/admin/contact-management/${contactId}/status`,
        method: 'PUT',
        data: { status: status },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                setTimeout(() => location.reload(), 1000);
            }
        },
        error: function() {
            showAlert('danger', 'Failed to update status. Please try again.');
        }
    });
}

// Delete Query
function deleteQuery(contactId) {
    if (!confirm('Are you sure you want to delete this query? This action cannot be undone.')) return;
    
    $.ajax({
        url: `/admin/contact-management/${contactId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                setTimeout(() => location.reload(), 1000);
            }
        },
        error: function() {
            showAlert('danger', 'Failed to delete query. Please try again.');
        }
    });
}

// Bulk Actions
function showBulkActions() {
    $('#bulkActionsBar').show();
    updateSelectedCount();
}

function hideBulkActions() {
    $('#bulkActionsBar').hide();
    $('.bulk-checkbox').prop('checked', false);
    $('#selectAll').prop('checked', false);
}

function updateSelectedCount() {
    const count = $('.bulk-checkbox:checked').length;
    $('#selectedCount').text(count);
    
    if (count === 0) {
        hideBulkActions();
    }
}

// Select All Checkbox
$('#selectAll').on('change', function() {
    $('.bulk-checkbox').prop('checked', $(this).is(':checked'));
    updateSelectedCount();
});

// Individual Checkboxes
$(document).on('change', '.bulk-checkbox', function() {
    updateSelectedCount();
    
    // Update select all checkbox
    const totalCheckboxes = $('.bulk-checkbox').length;
    const checkedCheckboxes = $('.bulk-checkbox:checked').length;
    $('#selectAll').prop('checked', totalCheckboxes === checkedCheckboxes);
});

// Bulk Actions
function bulkAction(action) {
    const selectedIds = $('.bulk-checkbox:checked').map(function() {
        return $(this).val();
    }).get();
    
    if (selectedIds.length === 0) {
        showAlert('warning', 'Please select at least one query.');
        return;
    }
    
    const actionText = {
        'delete': 'delete',
        'mark_read': 'mark as read',
        'mark_resolved': 'mark as resolved',
        'mark_archived': 'archive'
    };
    
    if (!confirm(`Are you sure you want to ${actionText[action]} ${selectedIds.length} selected queries?`)) return;
    
    $.ajax({
        url: '/admin/contact-management/bulk-action',
        method: 'POST',
        data: {
            action: action,
            contact_ids: selectedIds
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                setTimeout(() => location.reload(), 1000);
            }
        },
        error: function() {
            showAlert('danger', 'Failed to perform bulk action. Please try again.');
        }
    });
}

// Export Queries
function exportQueries() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '/admin/contact-management/export?' + params.toString();
}

// Alert Helper
function showAlert(type, message) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `;
    
    $('.content-wrapper').prepend(alertHtml);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}
</script>
@endpush

@push('styles')
<style>
.table-responsive {
    max-height: 600px;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.bulk-checkbox-main, .bulk-checkbox {
    cursor: pointer;
}

#bulkActionsBar {
    border-left: 4px solid #007bff;
}

.small-box {
    border-radius: 0.25rem;
}

.small-box .icon {
    font-size: 90px;
}
</style>
@endpush
