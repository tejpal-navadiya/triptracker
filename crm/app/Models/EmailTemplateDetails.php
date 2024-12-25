<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmailTemplateDetails extends Model
{
    use HasFactory;
    protected $fillable = ['id','emt_id', 'category_id','traveller_id','email_subject','email_text','status','emt_status'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_email_template_details');
    }
    public function email_category()
    {
        return $this->belongsTo(EmailCategory::class, 'category_id', 'email_cat_id');
    }

    public function lead_traveler()
    {
        return $this->belongsTo(TripTravelingMember::class, 'traveller_id', 'trtm_id');
    }

}
