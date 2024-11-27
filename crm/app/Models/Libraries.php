<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libraries extends Model
{
    use HasFactory;
    protected $primaryKey = 'lib_id'; // Set the primary key
    public $incrementing = true;     // Set to false if it's not auto-incrementing
    protected $keyType = 'integer';    // Set to 'string' if the primary key is not an integer
    public $table = "library";
    protected $fillable = ['lib_id',
                           'lib_category',	
                            'lib_name',
                            'tag_name',
                            'lib_basic_information',
                            'lib_image',
                            'lib_status',
                            ];

        public function libcategory()
        {
            return $this->belongsTo(LibrariesCatgory::class, 'lib_category', 'lib_cat_id');
        }
    


}
