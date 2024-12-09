<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MailInbox extends Model
{
    use HasFactory;
    protected $fillable = ['inbox_id','id', 'tr_id' ,'inbox_subject','inbox_from','inbox_date','inbox_body','uniq_id','last_email_id','inbox_status'];

      public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_mail_inbox');
    }

}
