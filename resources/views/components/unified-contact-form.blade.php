{{-- 
    Unified Contact Form Component
    
    Usage:
    @include('components.unified-contact-form', [
        'form_source' => 'footer',
        'form_variant' => 'compact',
        'show_phone' => true,
        'show_subject' => true,
        'recaptcha_site_key' => config('services.recaptcha.site_key')
    ])
--}}

@php
    $form_source = $form_source ?? 'default';
    $form_variant = $form_variant ?? 'standard';
    $show_phone = $show_phone ?? true;
    $show_subject = $show_subject ?? true;
    $recaptcha_site_key = $recaptcha_site_key ?? config('services.recaptcha.site_key');
    $form_id = 'unified-contact-form-' . Str::random(8);
@endphp

<form id="{{ $form_id }}" action="{{ route('contact.submit') }}" method="POST" novalidate class="unified-contact-form">
    @csrf
    
    <!-- Honeypot field for spam protection -->
    <div style="display: none;">
        <input type="text" name="website" value="" autocomplete="off" tabindex="-1">
    </div>
    
    <!-- Hidden fields for analytics -->
    <input type="hidden" name="form_source" value="{{ $form_source }}">
    <input type="hidden" name="form_variant" value="{{ $form_variant }}">
    
    <div class="row">
        <!-- Name Field -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="{{ $form_id }}_name">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="{{ $form_id }}_name" 
                       placeholder="Your Full Name" required value="{{ old('name') }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        
        <!-- Email Field -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="{{ $form_id }}_email">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" name="email" id="{{ $form_id }}_email" 
                       placeholder="your@email.com" required value="{{ old('email') }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    
    <div class="row">
        @if($show_phone)
        <!-- Phone Field -->
        <div class="col-md-6">
            <div class="form-group">
                <label for="{{ $form_id }}_phone">Phone</label>
                <input type="tel" class="form-control" name="phone" id="{{ $form_id }}_phone" 
                       placeholder="+61 400 000 000" value="{{ old('phone') }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        @endif
        
        @if($show_subject)
        <!-- Subject Field -->
        <div class="col-md-{{ $show_phone ? '6' : '12' }}">
            <div class="form-group">
                <label for="{{ $form_id }}_subject">Subject <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="subject" id="{{ $form_id }}_subject" 
                       placeholder="What can we help you with?" required value="{{ old('subject') }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Message Field -->
    <div class="form-group">
        <label for="{{ $form_id }}_message">Message <span class="text-danger">*</span></label>
        <textarea class="form-control" name="message" id="{{ $form_id }}_message" rows="5" 
                  placeholder="Please describe your inquiry in detail..." required>{{ old('message') }}</textarea>
        <div class="invalid-feedback"></div>
    </div>
    
    @if($recaptcha_site_key)
    <!-- reCAPTCHA -->
    <div class="form-group">
        <div class="g-recaptcha" data-sitekey="{{ $recaptcha_site_key }}"></div>
        <div class="invalid-feedback"></div>
    </div>
    @endif
    
    <!-- Submit Button -->
    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-lg">
            <span class="submit-text">Send Message</span>
            <span class="loading-text" style="display: none;">
                <i class="fa fa-spinner fa-spin"></i> Sending...
            </span>
        </button>
    </div>
</form>

<!-- Feedback Area -->
<div id="{{ $form_id }}_feedback" class="contact-form-feedback" aria-live="polite" style="margin-top: 15px;"></div>

@push('scripts')
@if($recaptcha_site_key)
<!-- Google reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('{{ $form_id }}');
    const feedback = document.getElementById('{{ $form_id }}_feedback');
    const submitBtn = form.querySelector('button[type="submit"]');
    const submitText = submitBtn.querySelector('.submit-text');
    const loadingText = submitBtn.querySelector('.loading-text');
    
    // Clear previous validation states
    function clearValidation() {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        feedback.className = 'contact-form-feedback';
        feedback.textContent = '';
    }
    
    // Show field error
    function showFieldError(fieldName, message) {
        const field = form.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.classList.add('is-invalid');
            const errorDiv = field.parentNode.querySelector('.invalid-feedback');
            if (errorDiv) {
                errorDiv.textContent = message;
            }
        }
    }
    
    // Show general feedback
    function showFeedback(message, type = 'info') {
        feedback.className = `contact-form-feedback alert alert-${type}`;
        feedback.textContent = message;
        feedback.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    
    // Set loading state
    function setLoading(loading) {
        submitBtn.disabled = loading;
        submitText.style.display = loading ? 'none' : 'inline';
        loadingText.style.display = loading ? 'inline' : 'none';
    }
    
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        
        clearValidation();
        setLoading(true);
        
        const formData = new FormData(form);
        
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                showFeedback(data.message || 'Thank you! We will get back to you soon.', 'success');
                form.reset();
                
                @if($recaptcha_site_key)
                // Reset reCAPTCHA if present
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
                @endif
            } else {
                // Handle validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(fieldName => {
                        const messages = data.errors[fieldName];
                        if (messages && messages.length > 0) {
                            showFieldError(fieldName, messages[0]);
                        }
                    });
                }
                
                showFeedback(data.message || 'There were validation errors. Please check the form and try again.', 'danger');
            }
        } catch (error) {
            console.error('Contact form error:', error);
            showFeedback('Network error. Please check your connection and try again.', 'danger');
        } finally {
            setLoading(false);
        }
    });
});
</script>
@endpush

@push('styles')
<style>
.unified-contact-form .form-group {
    margin-bottom: 1.5rem;
}

.unified-contact-form .form-control.is-invalid {
    border-color: #dc3545;
}

.unified-contact-form .invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

.contact-form-feedback {
    border-radius: 0.25rem;
    padding: 0.75rem 1.25rem;
    margin-bottom: 1rem;
}

.contact-form-feedback.alert-success {
    color: #155724;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
}

.contact-form-feedback.alert-danger {
    color: #721c24;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
}

.contact-form-feedback.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
}

.unified-contact-form button[type="submit"]:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.hidden {
    position: absolute;
    left: -9999px;
    width: 1px;
    height: 1px;
    overflow: hidden;
}
</style>
@endpush
