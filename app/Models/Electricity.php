<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Electricity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'image',
        'fee',
        'minimum',
        'msorg1',
        'msorg2',
        'msorg3',
        'msorg6',
        'msorg4',
        'msorg5',
        'adex1',
        'adex2',
        'adex3',
        'adex4',
        'adex5',
        'adex6',
        'easyaccess',
        'clubkonnect',
        'ibrolinks',
        'ncwallet',
        'vtpass',
    ];
}
