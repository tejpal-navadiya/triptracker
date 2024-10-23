<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['tr_id','id','tr_name', 'tr_agent_id', 'tr_traveler_name', 'tr_dob', 'tr_age', 'tr_number', 'tr_email', 'tr_phone', 'tr_num_people','tr_start_date' ,'tr_end_date' ,'tr_value_trip' , 'tr_type_trip','tr_desc','tr_status'];

    
    protected $tableSetManually = false; 

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        if ($user && !$this->tableSetManually) { 
            $uniq_id = $user->user_id;
            $this->setTable($uniq_id . '_tc_trip');
        }
    }

    public function setTableForUniqueId($uniqueId)
    {
        $this->setTable($uniqueId . '_tc_trip');
        $this->tableSetManually = true; 
    }

    protected $casts = [
        'id' => 'string',
    ];

    
}
