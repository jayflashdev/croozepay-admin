<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBundle extends Model
{
    use HasFactory;

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    protected $fillable = [
        'name',
        'network_id',
        'type',
        'service',
        'status',
        'size',
        'day',
        'price',
        'reseller',
        'api',
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
