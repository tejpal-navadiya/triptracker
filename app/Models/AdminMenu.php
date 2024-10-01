<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;

class AdminMenu extends Model
{
    use HasFactory;
    public $table = "ta_admin_menu";

    protected $fillable = [
        'mname',
        'mtitle',
        'pmenu',
        'is_deleted'
    ];

    public function userAccess()
    {
        return $this->hasMany(UserAccess::class, 'mid');
    }

    public function permissions()
    {
        return $this->belongsToMany(AdminMenu::class, 'permission_menu', 'menu_id', 'permission_id');
    }

}
