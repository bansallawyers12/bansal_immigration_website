<!DOCTYPE html>
   <html>
   <head>
       <title>Send SMS</title>
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
   </head>
   <body>
       <div class="container mt-5">
           <h1>Send SMS</h1>
           @if(session('success'))
               <div class="alert alert-success">{{ session('success') }}</div>
           @endif
           @if(session('error'))
               <div class="alert alert-danger">{{ session('error') }}</div>
           @endif
           <form method="POST" action="{{ route('sms.send') }}">
               @csrf
               <div class="mb-3">
                   <label for="phone" class="form-label">Phone Number</label>
                   <input type="text" class="form-control" id="phone" name="phone" required>
               </div>
               <div class="mb-3">
                   <label for="message" class="form-label">Message</label>
                   <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
               </div>
               <button type="submit" class="btn btn-primary">Send SMS</button>
           </form>
       </div>
   </body>
   </html>
