<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripType extends Model
{
    use HasFactory;
    public $table = "ta_trip_type";
    protected $fillable = [
        'ty_id',
        'ty_name',
        'ty_status'
    ];
}
