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
    protected $fillable = ['id','users_name', 'users_email', 'users_phone', 'users_password', 'role_id','user_id','users_status', 'users_image', 'country_id', 'state_id', 'users_city_name', 'users_pincode'];
    
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
