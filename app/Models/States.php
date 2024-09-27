<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    use HasFactory;
    public $table = "ta_states";

    public function country()
    {
        return $this->belongsTo(Countries::class);
    }
}
