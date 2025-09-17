<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forwarded Query: {{ $contact->subject }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .forward-info {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 0 4px 4px 0;
        }
        .forward-info h3 {
            margin: 0 0 10px 0;
            color: #1976d2;
            font-size: 16px;
        }
        .forward-info p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }
        .query-details {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            margin-bottom: 12px;
            align-items: flex-start;
        }
        .detail-label {
            font-weight: 600;
            color: #495057;
            min-width: 100px;
            margin-right: 15px;
        }
        .detail-value {
            flex: 1;
            color: #6c757d;
        }
        .message-content {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
            font-size: 15px;
            line-height: 1.7;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            margin: 10px 5px;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #0056b3;
            color: white;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #545b62;
        }
        .actions {
            text-align: center;
            margin: 25px 0;
        }
        @media (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 20px;
            }
            .detail-row {
                flex-direction: column;
            }
            .detail-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>📧 Forwarded Contact Query</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Forward Information -->
            <div class="forward-info">
                <h3>🔄 Query Forwarded by {{ $forwarded_by }}</h3>
                <p><strong>Forwarded on:</strong> {{ $forwarded_at->format('F j, Y \a\t g:i A') }}</p>
                @if($forward_message)
                <p><strong>Note:</strong> {{ $forward_message }}</p>
                @endif
            </div>

            @if($include_original)
            <!-- Original Query Details -->
            <h2 style="color: #495057; margin-bottom: 20px;">📋 Original Query Details</h2>
            
            <div class="query-details">
                <div class="detail-row">
                    <div class="detail-label">From:</div>
                    <div class="detail-value">
                        <strong>{{ $contact->name ?: 'N/A' }}</strong><br>
                        <a href="mailto:{{ $contact->contact_email }}" style="color: #007bff;">{{ $contact->contact_email }}</a>
                        @if($contact->contact_phone)
                        <br><a href="tel:{{ $contact->contact_phone }}" style="color: #007bff;">{{ $contact->contact_phone }}</a>
                        @endif
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Subject:</div>
                    <div class="detail-value">{{ $contact->subject ?: 'No subject provided' }}</div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Received:</div>
                    <div class="detail-value">{{ $contact->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>

                @if($contact->form_source)
                <div class="detail-row">
                    <div class="detail-label">Source:</div>
                    <div class="detail-value">
                        {{ ucfirst($contact->form_source) }}
                        @if($contact->form_variant) ({{ $contact->form_variant }}) @endif
                    </div>
                </div>
                @endif

                @if($contact->ip_address)
                <div class="detail-row">
                    <div class="detail-label">IP Address:</div>
                    <div class="detail-value">{{ $contact->ip_address }}</div>
                </div>
                @endif
            </div>

            <!-- Message Content -->
            <h3 style="color: #495057; margin-bottom: 15px;">💬 Message Content</h3>
            <div class="message-content">
                {!! nl2br(e($contact->message)) !!}
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="actions">
                <a href="mailto:{{ $contact->contact_email }}?subject=Re: {{ urlencode($contact->subject ?: 'Your inquiry') }}" class="btn">
                    📧 Reply to Customer
                </a>
                @if(config('app.url'))
                <a href="{{ config('app.url') }}/admin/contact-management/{{ $contact->id }}" class="btn btn-secondary">
                    👁️ View in Admin Panel
                </a>
                @endif
            </div>

            <!-- Additional Information -->
            <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 6px; padding: 15px; margin-top: 25px;">
                <p style="margin: 0; font-size: 14px; color: #856404;">
                    <strong>📝 Note:</strong> This query has been forwarded to you for follow-up. 
                    Please respond to the customer directly or coordinate with the team as needed.
                    The original query status in the admin panel will be updated automatically.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This email was automatically generated by the Bansal Immigration contact management system.</p>
            <p>© {{ date('Y') }} Bansal Immigration. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
