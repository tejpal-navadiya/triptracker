<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PredefineTaskCategory extends Model
{
    use HasFactory;
    public $table = "predefine_task_category";
    protected $fillable = [
        'task_cat_id',
        'task_cat_name',
        'task_cat_status'
    ];

}
