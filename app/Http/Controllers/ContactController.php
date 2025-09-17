<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Mail;
use App\Contact;
use App\Enquiry;
use App\Mail\ContactUsMail;
use App\Mail\ContactUsCustomerMail;

class ContactController extends Controller
{
    /**
     * Handle unified contact form submission
     * Supports both AJAX and traditional form submission
     * Includes reCAPTCHA validation, rate limiting, and spam protection
     */
    public function contactSubmit(Request $request)
    {
        // Check if request is AJAX
        $isAjax = $request->ajax() || $request->wantsJson() || $request->hasHeader('X-Requested-With');

        try {
            // Rate limiting: max 5 attempts per minute per IP
            $key = 'contact-form:' . $request->ip();
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $errorMessage = 'Too many attempts. Please try again later.';
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'errors' => ['rate_limit' => [$errorMessage]]
                    ], 422);
                }
                
                return back()->withErrors(['rate_limit' => $errorMessage]);
            }

            // Increment rate limiter
            RateLimiter::hit($key, 60);

            // Validate honeypot (spam protection)
            if (!empty($request->website)) {
                $errorMessage = 'Spam detected';
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'errors' => ['spam' => [$errorMessage]]
                    ], 422);
                }
                
                return back()->withErrors(['spam' => $errorMessage]);
            }

            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|email:rfc,dns|max:255',
                'phone' => 'nullable|string|max:20|regex:/^[\+]?[(]?[0-9]{1,4}[)]?[-\s\.0-9]*$/',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:2000',
                'g-recaptcha-response' => 'required',
                'form_source' => 'nullable|string|max:50',
                'form_variant' => 'nullable|string|max:50',
            ], [
                'name.required' => 'Name is required.',
                'name.regex' => 'Name should only contain letters and spaces.',
                'email.required' => 'Email is required.',
                'email.email' => 'Please enter a valid email address.',
                'phone.regex' => 'Please enter a valid phone number.',
                'subject.required' => 'Subject is required.',
                'message.required' => 'Message is required.',
                'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
            ]);

            if ($validator->fails()) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return back()->withErrors($validator)->withInput();
            }

            // Verify reCAPTCHA
            if (!$this->validateRecaptcha($request->input('g-recaptcha-response'))) {
                $errorMessage = 'reCAPTCHA validation failed';
                
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'errors' => ['g-recaptcha-response' => ['Please complete the reCAPTCHA verification.']]
                    ], 422);
                }
                
                return back()->withErrors(['g-recaptcha-response' => 'Please complete the reCAPTCHA verification.'])->withInput();
            }

            // Sanitize inputs
            $data = [
                'name' => filter_var($request->name, FILTER_SANITIZE_STRING),
                'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
                'phone' => filter_var($request->phone, FILTER_SANITIZE_STRING),
                'subject' => filter_var($request->subject, FILTER_SANITIZE_STRING),
                'message' => filter_var($request->message, FILTER_SANITIZE_STRING),
                'form_source' => filter_var($request->form_source, FILTER_SANITIZE_STRING),
                'form_variant' => filter_var($request->form_variant, FILTER_SANITIZE_STRING),
            ];

            // Create Contact record
            $contact = new Contact();
            $contact->name = $data['name'];
            $contact->contact_email = $data['email'];
            $contact->contact_phone = $data['phone'];
            $contact->subject = $data['subject'];
            $contact->message = $data['message'];
            $contact->ip_address = $request->ip();
            $contact->status = 'unread';
            
            // Optional metadata for analytics
            if ($data['form_source']) {
                $contact->form_source = $data['form_source'];
            }
            if ($data['form_variant']) {
                $contact->form_variant = $data['form_variant'];
            }
            
            $contact->save();

            // Create Enquiry record
            $enquiry = new Enquiry();
            $enquiry->first_name = $data['name'];
            $enquiry->email = $data['email'];
            $enquiry->phone = $data['phone'];
            $enquiry->subject = $data['subject'];
            $enquiry->message = $data['message'];
            $enquiry->ip_address = $request->ip();
            $enquiry->save();

            // Send email notification to admin
            $adminEmail = config('mail.from.address');
            $emailSubject = 'New Contact Form Inquiry from ' . $data['name'];
            
            $emailDetails = [
                'title' => $emailSubject,
                'body' => $data['message'],
                'subject' => $emailSubject,
                'fullname' => 'Admin',
                'from' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'description' => $data['message'],
                'form_source' => $data['form_source'],
                'form_variant' => $data['form_variant'],
            ];

            Mail::to($adminEmail)->send(new ContactUsMail($emailDetails));

            // Optional: Send acknowledgment email to customer
            // Uncomment the following lines to enable customer acknowledgment emails
            /*
            $customerEmailDetails = [
                'title' => 'Thank you for contacting Bansal Immigration',
                'body' => 'We have received your inquiry and will respond within 24 hours.',
                'subject' => 'Your Inquiry to Bansal Immigration',
                'fullname' => $data['name'],
                'from' => 'Bansal Immigration Team',
                'email' => $data['email'],
                'phone' => $data['phone'],
                'description' => $data['message']
            ];
            
            Mail::to($data['email'])->send(new ContactUsCustomerMail($customerEmailDetails));
            */

            $successMessage = 'Thank you! Your message has been sent successfully. We\'ll get back to you within 24 hours.';

            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'message' => $successMessage
                ], 200);
            }

            return back()->with('success', $successMessage);

        } catch (\Exception $e) {
            \Log::error('Contact form submission error: ' . $e->getMessage());
            
            $errorMessage = 'Sorry, there was an error sending your message. Please try again.';
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Validate Google reCAPTCHA response
     */
    private function validateRecaptcha($recaptchaResponse)
    {
        if (empty($recaptchaResponse)) {
            return false;
        }

        $secretKey = config('services.recaptcha.secret');
        
        if (empty($secretKey)) {
            \Log::warning('reCAPTCHA secret key not configured');
            return true; // Allow form submission if reCAPTCHA is not configured
        }

        $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
        
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => request()->ip()
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($verifyUrl, false, $context);
        
        if ($result === false) {
            \Log::error('Failed to verify reCAPTCHA');
            return false;
        }

        $response = json_decode($result, true);
        
        return isset($response['success']) && $response['success'] === true;
    }
}
