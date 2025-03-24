<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'number',
        'discount',
        'reseller',
        'api_discount',
        'msorg1',
        'msorg2',
        'msorg3',
        'msorg6',
        'msorg4',
        'msorg5',
        'reseller_pin',
        'pin_discount',
        'api_pin_discount',
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
        'data_cg',
        'data_g',
        'data_sme',
        'rate',
    ];

    public function datasub()
    {
        return $this->hasMany(DataPlan::class);
    }

    public function datacards()
    {
        return $this->hasMany(DatacardPlan::class);
    }

    public function rechargecards()
    {
        return $this->hasMany(RechargePlan::class);
    }
}
