<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelingRelationship extends Model
{
    use HasFactory;

    public $table = "traveling_relationship";
    protected $fillable = [
        'rel_id',
        'rel_name',
        'rel_status'
    ];

}
