<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TripTask extends Model
{
    use HasFactory;

    protected $fillable = ['trvt_id','id','tr_id', 'trvt_name', 'trvt_agent_id', 'trvt_category', 'trvt_priority', 'trvt_date', 'trvt_due_date', 'trvt_document', 'status', 'trtm_relationship', 'trvt_status'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_traveling_task');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'tr_id', 'tr_id');
    }
    

    public function tripCategory()
    {
        return $this->belongsTo(TaskCategory::class, 'trvt_category', 'task_cat_id');
    }

    public function taskstatus()
    {
        return $this->belongsTo(TaskStatus::class, 'status', 'ts_status_id');
    }
    
    
    protected $casts = [
        'id' => 'string',
    ];





    

}
