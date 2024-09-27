<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class MasterAdminLogActivities extends Model
{
    use HasFactory;
    protected $fillable = ['subject', 'url', 'method', 'ip', 'agent', 'user_id'];

    public function setTableForUniqueId($uniqueId)
    {
        $this->setTable($uniqueId . '_tc_log_activities_table');
    }

    public function user()
    {
        // This method defines the relationship
        return $this->belongsTo(MasterUserDetails::class, 'user_id', 'users_id');
    }

}
