<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TripDocument extends Model
{
    use HasFactory;

    // trp_id	id	tr_id	trp_name	trp_document	trp_status	
    protected $fillable = ['trp_id','id','tr_id', 'trp_name','trp_document', 'trp_status'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_trip_document');
    }
}
