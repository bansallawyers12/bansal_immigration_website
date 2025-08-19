<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //Access the 'service' key from details array
        $serviceTitle = $this->details['service'] ?? 'Migration Consultation';
        //Define subject based on service type
        $subject = "Confirmation of Your {$serviceTitle} Appointment";
        return $this->subject($subject) // Set custom subject here
                    ->view('emails.appointment'); // Your email blade template
    }
}
