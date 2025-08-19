<!DOCTYPE html>
<html>
<head>
    <title>Send SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Send SMS</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Verification Section -->
                        <div class="mb-4" id="verificationSection">
                            <h5>Verify Phone Number</h5>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" id="verify_phone_number"
                                    placeholder="+1234567890">
                                <button class="btn btn-outline-secondary" type="button" id="sendCodeBtn">
                                    Send Code
                                </button>
                            </div>
                            <div id="verificationCodeSection" style="display: none;">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="verification_code"
                                        placeholder="Enter verification code">
                                    <button class="btn btn-outline-secondary" type="button" id="verifyCodeBtn">
                                        Verify Code
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- SMS Form -->
                        <form method="POST" action="{{ route('send.sms') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number"
                                    placeholder="+1234567890" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send SMS</button>
                        </form>

                        <!-- Verified Numbers Section -->
                        <div class="mt-4">
                            <h5>Verified Numbers</h5>
                            <ul class="list-group">
                                @foreach($verifiedNumbers as $number)
                                    <li class="list-group-item">
                                        {{ $number->phone_number }}
                                        <small class="text-muted">
                                            (Verified: {{ $number->verified_at->format('Y-m-d H:i:s') }})
                                        </small>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#sendCodeBtn').click(function() {
                const phoneNumber = $('#verify_phone_number').val();
                if (!phoneNumber) return;

                $.post('{{ route("verify.send-code") }}', {
                    phone_number: phoneNumber
                })
                .done(function(response) {
                    alert(response.message);
                    $('#verificationCodeSection').show();
                })
                .fail(function(xhr) {
                    alert('Failed to send verification code');
                });
            });

            $('#verifyCodeBtn').click(function() {
                const phoneNumber = $('#verify_phone_number').val();
                const code = $('#verification_code').val();
                if (!phoneNumber || !code) return;

                $.post('{{ route("verify.check-code") }}', {
                    phone_number: phoneNumber,
                    verification_code: code
                })
                .done(function(response) {
                    alert(response.message);
                    location.reload(); // Reload to show updated verified numbers list
                })
                .fail(function(xhr) {
                    alert(xhr.responseJSON?.message || 'Verification failed');
                });
            });
        });
    </script>
</body>
</html>
