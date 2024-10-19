<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class MasterUserDetails extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    // protected $fillable = ['id','users_name', 'users_email', 'users_phone', 'users_password', 'role_id','user_id','users_status', 'users_image', 'country_id', 'state_id', 'users_city_name', 'users_pincode'];

    protected $fillable = [
        'users_id',
        'id',
        'users_agencies_name',
        'users_franchise_name',
        'users_consortia_name',
        'users_first_name',
        'users_last_name',
        'users_email',
        'users_iata_clia_number',
        'users_clia_number',
        'users_iata_number',
        'users_password',
        'users_phone',
        'users_image',
        'users_address',
        'users_country',
        'users_state',
        'users_city',
        'users_zip',
        'role_id',
        'user_id',
        'user_status',
        'users_bio',
        'user_agency_numbers',
        'user_qualification',
        'user_work_email',
        'user_dob',
        'user_emergency_contact_person',
        'user_emergency_phone_number',
        'user_emergency_email',
    ];

    
    public function setTableForUniqueId($uniqueId)
    {
        $this->setTable($uniqueId . '_tc_users_details');
    }
    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'role_id');
    }

    protected $hidden = [
        'users_password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'users_password' => 'hashed',
        'id' => 'string',
    ];

    public function masterUser()
    {
        return $this->belongsTo(MasterUser::class, 'id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'users_country', 'id');
    }
    
    public function state()
    {
        return $this->belongsTo(States::class, 'users_state', 'id');
    }
    
    public function city()
    {
        return $this->belongsTo(Cities::class, 'users_city', 'id');
    }

}
