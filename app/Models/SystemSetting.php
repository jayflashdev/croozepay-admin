<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            \Cache::forget('SystemSettings');
        });
    }
}
