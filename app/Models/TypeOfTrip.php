<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TypeOfTrip extends Model
{
    use HasFactory;
    
    protected $fillable = ['trip_type_id','id','tr_id', 'trip_type_name', 'trip_type_text', 'trip_type_confirmation','trip_status'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_type_of_trip');
    }
}
