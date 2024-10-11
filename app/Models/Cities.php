<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Cities extends Model
{
    use HasFactory;

    public $table = "ta_cities";

    protected $fillable = ['id',
                           'name',
                            'state_id',
                            'state_code',	
                            'country_id'	,
                            'country_code', 
                            'latitude',
                            'longitude',
                             ];


    // public function __construct(array $attributes = [])
    // {
    //     parent::__construct($attributes);

    //     $user = Auth::guard('masteradmins')->user();
    //     // dd($user);
    //     $uniq_id = $user->user_id;
    //     $this->setTable($uniq_id . 'ta_cities');
    // }
}
