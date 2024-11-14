<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibrariesCatgory extends Model
{
    use HasFactory;
    public $table = "library_category";
    protected $fillable = [
        'lib_cat_id',
        'lib_cat_name',
        'lib_cat_status'
    ];


}
