<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LogActivities extends Model
{
    use HasFactory;

    protected $fillable = ['subject', 'url', 'method', 'ip', 'agent', 'user_id'];

    protected $table = 'log_activities_table';

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
