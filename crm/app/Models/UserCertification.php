<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserCertification extends Model
{
    use HasFactory;

   // INSERT INTO `host1lot_tc_users_certification`(`users_cert_id`, `id`, `users_cert_name`, `users_cert_person_name`, `users_cert_completed_date`, `users_cert_expiration`, `users_cert_desc`, `users_cert_document`, `users_status`, `created_at`, `updated_at`)

    protected $fillable = ['users_cert_id','id','users_cert_name', 'users_cert_person_name','users_cert_completed_date','users_cert_expiration','users_cert_desc','users_cert_document','users_status'];
    
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_users_certification');
    }
}
