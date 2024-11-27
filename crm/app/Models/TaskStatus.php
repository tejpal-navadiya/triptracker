<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    public $table = "task_status";
    protected $fillable = [
        'ts_status_id ',
        'ts_status_name',
        'status'
    ];
}
