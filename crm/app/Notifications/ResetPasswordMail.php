<?php 
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param  string  $token
     * @param  string  $email
     * @return void
     */
    public function __construct($token, $email)
    {   
        // dd($email);
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url(config('app.url') . config('global.businessAdminURL') . '/reset-password/' . $this->token . '?email=' . urlencode($this->email));

        return $this->view('components.forgot_password_mail')
                    ->subject('Reset Password Notification')
                    ->with([
                        'url' => $url,
                        'email' => $this->email,
                    ]);
    }
}
