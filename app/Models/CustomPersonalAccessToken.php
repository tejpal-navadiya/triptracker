<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\PersonalAccessToken;


class CustomPersonalAccessToken extends PersonalAccessToken
{
    use HasFactory;
    public $table = "personal_access_tokens";

    protected $fillable = ['id',
                    'tokenable_type',
                    'tokenable_id',
                    'name',	
                    'token'	,
                    'abilities', 
                    'last_used_at',
                    'expires_at',
                    'created_at',
                    'updated_at',
                        ];

    
}
