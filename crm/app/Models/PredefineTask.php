<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredefineTask extends Model
{
    use HasFactory;

    // INSERT INTO `predefine_task`(`pre_task_id`, `pre_task_name`, `pre_priority`, `pre_task_msg`, `pre_task_type`, `status`, `created_at`, `updated_at`) 
    public $table = "predefine_task";
    protected $fillable = [
        'pre_task_id',
        'pre_task_name',
        'pre_priority',
        'pre_task_msg',
        'pre_task_type',
        'status',
    ];




}
