<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TravelerDocument extends Model
{
    use HasFactory;
    protected $fillable = ['trvd_id','id','trv_id', 'trvm_id','trvd_name','trvd_document','trvd_status'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_traveling_document');
    }

    public function traveler()
    {
        return $this->belongsTo(TripTravelingMember::class, 'trvm_id', 'trtm_id');
    }

    public function documenttype()
    {
        return $this->belongsTo(DocumentType::class, 'trvd_name', 'docty_id');
    }


}
