<?php 
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $uniqueId;
    public $loginUrl;
    public $userEmail;
    public $invoiceUrl;


    /**
     * Create a new message instance.
     *
     * @param  string  $token
     * @param  string  $email
     * @return void
     */
    public function __construct($uniqueId, $loginUrl, $userEmail, $invoiceUrl)
    {   
        // dd($loginUrl);
        $this->uniqueId = $uniqueId;
        $this->loginUrl = $loginUrl;
        $this->userEmail = $userEmail;
        $this->invoiceUrl = $invoiceUrl;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('masteradmin.emails.user_registered')
                    ->subject('User Registration Complete')
                    ->with([
                        'uniqueId' => $this->uniqueId,
                        'loginUrl' => $this->loginUrl,
                        'userEmail' => $this->userEmail,
                        'invoiceUrl' => $this->invoiceUrl,
                    ]);
    }
}
