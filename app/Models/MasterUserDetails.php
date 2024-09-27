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
        'users_image',
        'users_address',
        'users_state',
        'users_city',
        'users_zip',
        'role_id',
        'user_id',
        'user_status',
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
        'users_phone',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'users_password' => 'hashed',
    ];

    public function masterUser()
    {
        return $this->belongsTo(MasterUser::class, 'id', 'id');
    }

  

}
