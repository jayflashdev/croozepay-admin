<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Betsite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'fee',
        'image', 'minimum',
        'clubkonnect',
        'code',
        'ncwallet',
        'vtpass',
    ];
}
