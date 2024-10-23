<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TripTravelingMember extends Model
{
    use HasFactory;
    
    protected $fillable = ['trtm_id','id','tr_id', 'trtm_type', 'trtm_first_name', 'trtm_middle_name', 'trtm_last_name', 'trtm_nick_name', 'trtm_gender', 'trtm_dob', 'trtm_age', 'trtm_relationship', 'trtm_status'];


    protected $tableSetManually = false; 

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        if ($user && !$this->tableSetManually) { 
            $uniq_id = $user->user_id;
            $this->setTable($uniq_id . '_tc_trip_traveling_member');
        }
    }

    public function setTableForUniqueId($uniqueId)
    {
        $this->setTable($uniqueId . '_tc_trip_traveling_member');
        $this->tableSetManually = true; 
    }
}
