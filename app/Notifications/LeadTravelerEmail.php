<?php 
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadTravelerEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData; // Variable to hold email data

    /**
     * Create a new message instance.
     *
     * @param array $emailData
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
        $email = $this->subject($this->emailData['subject'])
        ->view('masteradmin.emails.lead_traveler_email')
        ->with('data', $this->emailData);

            // Attach files, make sure this is an array
            if (isset($this->emailData['attachment']) && is_array($this->emailData['attachment'])) {
            foreach ($this->emailData['attachment'] as $file) {
            if (file_exists($file)) {
                $email->attach($file, [
                    'as' => basename($file),
                    'mime' => mime_content_type($file),
                ]);
            }
            }
            }

            return $email;
    }
}
