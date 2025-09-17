<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactForwardMail extends Mailable
{
    use Queueable, SerializesModels;
    
    public $emailData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $contact = $this->emailData['contact'];
        
        return $this->subject('Forwarded Query: ' . $contact->subject)
                    ->view('emails.contact_forward')
                    ->with([
                        'contact' => $contact,
                        'forward_message' => $this->emailData['forward_message'],
                        'include_original' => $this->emailData['include_original'],
                        'forwarded_by' => $this->emailData['forwarded_by'],
                        'forwarded_at' => $this->emailData['forwarded_at']
                    ]);
    }
}
