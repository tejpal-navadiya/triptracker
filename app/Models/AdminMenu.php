<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Plan;

class AdminMenu extends Model
{
    use HasFactory;
    public $table = "ta_admin_menu";

    public function subPermissions()
    {
        return $this->hasMany(AdminMenu::class, 'pmenu');
    }

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


}
