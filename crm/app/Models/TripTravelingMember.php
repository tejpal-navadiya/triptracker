<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TripTravelingMember extends Model
{
    use HasFactory;
    
    protected $fillable = ['trtm_id','id','tr_id', 'trtm_type', 'trtm_first_name', 'trtm_middle_name', 'trtm_last_name', 'trtm_nick_name', 'trtm_gender', 'trtm_dob', 'trtm_age', 'trtm_relationship', 'trtm_status','lead_id','lead_status','trtm_agent_id','trtm_email','trtm_number','trtm_notes','trtm_address','trtm_country','trtm_state','trtm_city','trtm_zip'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_trip_traveling_member');
    }

    public function travelingrelationship()
    {
        return $this->belongsTo(TravelingRelationship::class, 'trtm_relationship', 'rel_id');
    }
    
}
