<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class EmailTemplate extends Model
{
    use HasFactory;
    protected $fillable = ['id','email_tid', 'category','email_text'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_email_template');
    }
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'id', 'user_id');
    // }
}
