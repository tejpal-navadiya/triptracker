<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailsTemplates extends Model
{
    use HasFactory;

    public $table = "email_template";

    protected $fillable = ['email_tid', 'category','title','email_text'];

    public function emailcategory()
    {
        return $this->belongsTo(EmailCategories::class, 'category', 'email_cat_id');
    }
    

}
