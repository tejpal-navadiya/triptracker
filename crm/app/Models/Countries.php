<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Countries extends Model
{
    use HasFactory;
    public $table = "ta_countries";
    
    public function states()
    {
        return $this->hasMany(States::class);
    }


}
