<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripStatus extends Model
{
    use HasFactory;

    public $table = "trip_status";
    protected $fillable = [
        'tr_id',
        'tr_name',
        'tr_status'
    ];
}
