<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;


class MasterUser extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;
    protected $guard = 'masteradmin';

    protected $guarded = [];

    public $table = "buss_master_users";
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_agencies_name',
        'user_franchise_name',
        'user_consortia_name',
        'user_first_name',
        'user_last_name',
        'user_email',
        'user_iata_clia_number',
        'user_clia_number',
        'user_iata_number',
        'user_password',
        'user_image',
        'user_address',
        'user_country',
        'user_state',
        'user_city',
        'user_zip',
        'buss_unique_id',
        'sp_id',
        'isActive',
        'sp_expiry_date',
        'user_status',
    ];

    protected $hidden = [
        'user_password',
        'remember_token',
    ];
    
    protected $casts = [
        'email_verified_at' => 'datetime',
        'user_password' => 'hashed',
    ];

    public function userAccess()
    {
        return $this->hasMany(UserAccess::class, 'sp_id', 'sp_id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class, 'sp_id', 'sp_id');
    }
    public function pyUserDetails()
    {
        $detailsModel = new MasterUserDetails();
        $detailsModel->setTableForUniqueId($this->buss_unique_id);
        return $this->hasMany($detailsModel, 'user_id', 'id');
    }

}
