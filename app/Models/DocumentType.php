<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    public $table = "ta_document_type";
    protected $fillable = [
        'docty_id',
        'docty_name',
        'docty_status'
    ];
}
