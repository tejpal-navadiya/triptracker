<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class LibraryCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'lib_cat_id',
        'lib_cat_name',
        'lib_cat_status'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $user = Auth::guard('masteradmins')->user();
        // dd($user);
        $uniq_id = $user->user_id;
        $this->setTable($uniq_id . '_tc_library_category');
    }

}
