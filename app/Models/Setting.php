<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'title', 'name',
        'email', 'support_email',
        'description',
        'address',
        'phone',
        'logo',
        'favicon',
        'touch_icon',
        'facebook',
        'twitter', 'youtube',
        'whatsapp', 'whatsapp_group',
        'instagram', 'telegram',
        'primary_color',
        'sec_color', 'currency',
        'currency_code',
        'custom_js',
        'custom_css',
        'is_adsense',
        'meta_keywords', 'is_announcement', 'announcement', 'docs_link',
    ];

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            \Cache::forget('Settings');
        });
    }
}
