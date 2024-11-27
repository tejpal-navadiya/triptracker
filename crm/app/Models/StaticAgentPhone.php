<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticAgentPhone extends Model
{
    use HasFactory;

    public $table = "agent_phone";
    protected $fillable = [
        'agent_phone_id ',
        'Type'
    ];


}
