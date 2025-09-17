@extends('layouts.adminnew')

@section('title', 'View Contact Query')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Contact Query Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.contact-management.index') }}">Contact Queries</a></li>
                        <li class="breadcrumb-item active">View Query</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Query Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-envelope mr-2"></i>
                                {{ $contact->subject ?: 'No Subject' }}
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-{{ $contact->status === 'unread' ? 'warning' : ($contact->status === 'resolved' ? 'success' : ($contact->status === 'archived' ? 'secondary' : 'info')) }} badge-lg">
                                    {{ ucfirst($contact->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Contact Information -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="text-muted">From:</h6>
                                    <p class="mb-1">
                                        <strong>{{ $contact->name ?: 'N/A' }}</strong>
                                    </p>
                                    <p class="mb-1">
                                        <i class="fas fa-envelope text-muted mr-1"></i>
                                        <a href="mailto:{{ $contact->contact_email }}">{{ $contact->contact_email }}</a>
                                    </p>
                                    @if($contact->contact_phone)
                                    <p class="mb-1">
                                        <i class="fas fa-phone text-muted mr-1"></i>
                                        <a href="tel:{{ $contact->contact_phone }}">{{ $contact->contact_phone }}</a>
                                    </p>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted">Query Details:</h6>
                                    <p class="mb-1">
                                        <i class="fas fa-calendar text-muted mr-1"></i>
                                        {{ $contact->created_at->format('M d, Y \a\t H:i') }}
                                    </p>
                                    @if($contact->ip_address)
                                    <p class="mb-1">
                                        <i class="fas fa-globe text-muted mr-1"></i>
                                        {{ $contact->ip_address }}
                                    </p>
                                    @endif
                                    @if($contact->form_source)
                                    <p class="mb-1">
                                        <i class="fas fa-tag text-muted mr-1"></i>
                                        Source: {{ ucfirst($contact->form_source) }}
                                        @if($contact->form_variant)
                                            ({{ $contact->form_variant }})
                                        @endif
                                    </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Message Content -->
                            <div class="border-top pt-3">
                                <h6 class="text-muted">Message:</h6>
                                <div class="bg-light p-3 rounded">
                                    {!! nl2br(e($contact->message)) !!}
                                </div>
                            </div>

                            <!-- Forwarding Information -->
                            @if($contact->forwarded_to)
                            <div class="border-top pt-3 mt-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-share mr-2"></i>
                                    <strong>Forwarded</strong> to {{ $contact->forwarded_to }} 
                                    on {{ $contact->forwarded_at->format('M d, Y \a\t H:i') }}
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary" onclick="showForwardModal()">
                                    <i class="fas fa-share"></i> Forward Query
                                </button>
                                <button type="button" class="btn btn-success" onclick="replyToQuery()">
                                    <i class="fas fa-reply"></i> Reply via Email
                                </button>
                            </div>
                            <div class="btn-group ml-2">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-cog"></i> Actions
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" onclick="updateStatus('read')">
                                        <i class="fas fa-eye"></i> Mark as Read
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="updateStatus('resolved')">
                                        <i class="fas fa-check"></i> Mark as Resolved
                                    </a>
                                    <a class="dropdown-item" href="#" onclick="updateStatus('archived')">
                                        <i class="fas fa-archive"></i> Archive
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#" onclick="deleteQuery()">
                                        <i class="fas fa-trash"></i> Delete Query
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('admin.contact-management.index') }}" class="btn btn-outline-secondary float-right">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Quick Actions</h3>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <button class="list-group-item list-group-item-action" onclick="updateStatus('resolved')">
                                    <i class="fas fa-check text-success mr-2"></i>
                                    Mark as Resolved
                                </button>
                                <button class="list-group-item list-group-item-action" onclick="showForwardModal()">
                                    <i class="fas fa-share text-primary mr-2"></i>
                                    Forward to Team Member
                                </button>
                                <button class="list-group-item list-group-item-action" onclick="replyToQuery()">
                                    <i class="fas fa-reply text-info mr-2"></i>
                                    Reply via Email
                                </button>
                                <button class="list-group-item list-group-item-action" onclick="updateStatus('archived')">
                                    <i class="fas fa-archive text-secondary mr-2"></i>
                                    Archive Query
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Status History -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Status Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="time-label">
                                    <span class="bg-primary">{{ $contact->created_at->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <i class="fas fa-envelope bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> {{ $contact->created_at->format('H:i') }}</span>
                                        <h3 class="timeline-header">Query Received</h3>
                                        <div class="timeline-body">
                                            Query submitted via {{ $contact->form_source ? ucfirst($contact->form_source) : 'website' }} form
                                        </div>
                                    </div>
                                </div>
                                
                                @if($contact->status !== 'unread')
                                <div>
                                    <i class="fas fa-eye bg-yellow"></i>
                                    <div class="timeline-item">
                                        <h3 class="timeline-header">Status: {{ ucfirst($contact->status) }}</h3>
                                    </div>
                                </div>
                                @endif
                                
                                @if($contact->forwarded_to)
                                <div>
                                    <i class="fas fa-share bg-green"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> {{ $contact->forwarded_at->format('H:i') }}</span>
                                        <h3 class="timeline-header">Forwarded</h3>
                                        <div class="timeline-body">
                                            Forwarded to {{ $contact->forwarded_to }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($enquiry)
                    <!-- Related Enquiry -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Related Enquiry Record</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>ID:</strong> {{ $enquiry->id }}</p>
                            <p><strong>Name:</strong> {{ $enquiry->first_name }} {{ $enquiry->last_name }}</p>
                            <p><strong>Email:</strong> {{ $enquiry->email }}</p>
                            @if($enquiry->phone)
                            <p><strong>Phone:</strong> {{ $enquiry->phone }}</p>
                            @endif
                            <p><strong>Created:</strong> {{ $enquiry->created_at->format('M d, Y H:i') }}</p>
                        </div>
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
// Forward Modal
function showForwardModal() {
    $('#forwardModal').modal('show');
    $('#forwardForm')[0].reset();
    $('#forwardForm .is-invalid').removeClass('is-invalid');
    $('#forwardForm .invalid-feedback').text('');
}

// Forward Form Submit
$('#forwardForm').on('submit', function(e) {
    e.preventDefault();
    
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
        url: `/admin/contact-management/{{ $contact->id }}/forward`,
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
function updateStatus(status) {
    if (!confirm(`Are you sure you want to mark this query as ${status}?`)) return;
    
    $.ajax({
        url: `/admin/contact-management/{{ $contact->id }}/status`,
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
function deleteQuery() {
    if (!confirm('Are you sure you want to delete this query? This action cannot be undone.')) return;
    
    $.ajax({
        url: `/admin/contact-management/{{ $contact->id }}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showAlert('success', response.message);
                setTimeout(() => {
                    window.location.href = '{{ route("admin.contact-management.index") }}';
                }, 1000);
            }
        },
        error: function() {
            showAlert('danger', 'Failed to delete query. Please try again.');
        }
    });
}

// Reply to Query
function replyToQuery() {
    const email = '{{ $contact->contact_email }}';
    const subject = 'Re: {{ addslashes($contact->subject ?: "Your inquiry") }}';
    const mailtoLink = `mailto:${email}?subject=${encodeURIComponent(subject)}`;
    window.location.href = mailtoLink;
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
.timeline {
    position: relative;
    margin: 0 0 30px 0;
    padding: 0;
    list-style: none;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #dee2e6;
    left: 31px;
    margin: 0;
    border-radius: 2px;
}

.timeline > div {
    position: relative;
    margin: 0 0 20px 0;
}

.timeline > div > .timeline-item {
    box-shadow: 0 1px 1px rgba(0,0,0,0.1);
    border-radius: 3px;
    margin-top: -10px;
    background: #fff;
    color: #444;
    margin-left: 60px;
    margin-right: 15px;
    padding: 10px;
    position: relative;
}

.timeline > div > .fas,
.timeline > div > .far,
.timeline > div > .fab {
    width: 30px;
    height: 30px;
    font-size: 15px;
    line-height: 30px;
    position: absolute;
    color: #666;
    background: #f4f4f4;
    border-radius: 50%;
    text-align: center;
    left: 18px;
    top: 0;
}

.timeline > div > .bg-blue {
    background-color: #007bff !important;
    color: #fff;
}

.timeline > div > .bg-yellow {
    background-color: #ffc107 !important;
    color: #212529;
}

.timeline > div > .bg-green {
    background-color: #28a745 !important;
    color: #fff;
}

.timeline-header {
    margin: 0;
    color: #555;
    border-bottom: 1px solid #f4f4f4;
    padding: 0 0 10px 0;
    font-size: 16px;
    line-height: 1.1;
}

.timeline-body {
    padding: 10px 0 0 0;
}

.time-label > span {
    font-weight: 600;
    color: #fff;
    border-radius: 4px;
    display: inline-block;
    padding: 5px 10px;
}

.list-group-item-action {
    cursor: pointer;
}

.list-group-item-action:hover {
    background-color: #f8f9fa;
}
</style>
@endpush
