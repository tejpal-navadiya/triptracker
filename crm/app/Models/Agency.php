<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'age_user_id',
        'age_user_first_name',
        'age_user_last_name',
        'age_user_qualification',
        'age_user_work_email',
        'age_user_personal_email',
        'age_user_birthdate',
        'age_user_type',
        'age_user_password',
        'age_user_emergency_password',
        'age_user_phone_number',
        'age_user_emergency_email',
        'age_user_address',
        'age_user_city',
        'age_user_state_type',
        'age_user_zip',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_agency');
    }
}
