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

    public $user_id;

    /**
     * Create a new message instance.
     *
     * @param  string  $token
     * @param  string  $email
     * @return void
     */
    public function __construct($token, $email,$user_id)
    {   
        // dd($email);
        $this->token = $token;
        $this->email = $email;
        $this->user_id = $user_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = url(config('app.url') .'/'. config('global.businessAdminURL') . '/reset-password/' . $this->token . '?email=' . urlencode($this->email).'&user_id=' . urlencode($this->user_id));
        //$logoUrl = url('public/dist/img/logo.png');

        return $this->view('components.forgot_password_mail')
                    ->subject('Reset Password')
                    ->with([
                        'url' => $url,
                        'email' => $this->email
                    ]);
    }
}
