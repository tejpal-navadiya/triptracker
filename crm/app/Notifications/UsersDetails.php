<?php 
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsersDetails extends Mailable
{
    use Queueable, SerializesModels;

    public $uniqueId;
    public $loginUrl;
    public $userEmail;


    /**
     * Create a new message instance.
     *
     * @param  string  $token
     * @param  string  $email
     * @return void
     */
    public function __construct($uniqueId, $loginUrl, $userEmail)
    {   
        // dd($loginUrl);
        $this->uniqueId = $uniqueId;
        $this->loginUrl = $loginUrl;
        $this->userEmail = $userEmail;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('masteradmin.emails.user_details')
                    ->subject('Change Password')
                    ->with([
                        'uniqueId' => $this->uniqueId,
                        'loginUrl' => $this->loginUrl,
                        'userEmail' => $this->userEmail,
                    ]);
    }
}
