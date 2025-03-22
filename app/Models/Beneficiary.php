<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'number',
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeNetwork($query)
    {
        return $query->where('type', 'network');
    }

    public function scopeCable($query)
    {
        return $query->where('type', 'cable');
    }

    public function scopePower($query)
    {
        return $query->where('type', 'power');
    }

    public function scopeBet($query)
    {
        return $query->where('type', 'bet');
    }
}
