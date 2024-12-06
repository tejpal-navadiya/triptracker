<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MailSettings extends Model
{
    use HasFactory;

    protected $fillable = ['mail_smtp_id','id', 'mail_username' ,'mail_password','mail_outgoing_port','mail_incomiing_port','mail_outgoing_host','mail_incoming_host','mail_smtp_status'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_mail_smtp_settings');
    }

}
