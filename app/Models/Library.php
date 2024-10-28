<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Library extends Model
{
    use HasFactory;

    protected $fillable = ['lib_id',
                           'id', 
                           'lib_category',	
                           'lib_name',
                            'lib_currency',
                            'lib_country',	
                            'lib_state'	,
                            'lib_city', 
                            'lib_zip',
                            'lib_basic_information',
                            'lib_sightseeing_information',
                            'lib_image',
                            'lib_status',
                            ];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_library');
    }

    public function libcategory()
    {
        return $this->belongsTo(LibraryCategory::class, 'lib_category', 'lib_cat_id');
    }

    public function currency()
    {
        return $this->belongsTo(Countries::class, 'lib_currency', 'id');
    }

    public function state()
    {
        return $this->belongsTo(States::class, 'lib_state', 'id');
    }

    public function city()
    {
        return $this->belongsTo(Cities::class, 'lib_city', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Countries::class, 'lib_country', 'id');
    }
    
}
