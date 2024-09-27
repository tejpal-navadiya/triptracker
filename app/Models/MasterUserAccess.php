<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MasterUserAccess extends Model
{
    use HasFactory;
    // public $table = "py_master_user_access";

    protected $fillable = [
        'role_id',
        'u_id',
        'mname',
        'mtitle',
        'mid',
        'is_access'
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Dynamically set the table name
        $user = Auth::guard('masteradmins')->user();
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_master_user_access');
    }
    public function adminmenu()
    {
        return $this->hasMany(AdminMenu::class, 'pmenu');
    }

    public function userRole()
    {
        return $this->belongsTo(UserRole::class, 'role_id', 'role_id');
    }
    
}
