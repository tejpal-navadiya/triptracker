<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredefineTask extends Model
{
    use HasFactory;


    public $table = "predefine_task";
    protected $fillable = [
        'pre_task_id',
        'pre_task_name',
        'pre_task_msg',
        'status',
    ];




}
