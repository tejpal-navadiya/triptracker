<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailCategories extends Model
{
    use HasFactory;
    public $table = "email_category";
    protected $fillable = [
        'email_cat_id',
        'email_cat_name',
        'email_cat_status'
    ];
}
