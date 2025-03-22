<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount', 'user_id', 'type', 'code', 'new_balance', 'title',
        'old_balance', 'service', 'charge', 'message', 'status', 'response', 'meta',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'response' => 'object',
        'meta' => 'object',
    ];

    // search scope
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $params = ['user:username', 'user:name', 'user:id', 'user:email'];
            $query->where(function ($q) use ($params, $search) {
                foreach ($params as $key => $param) {
                    $relationData = explode(':', $param);

                    if (@$relationData[1]) {
                        $q = $this->relationSearch($q, $relationData[0], $relationData[1], $search);
                    } else {
                        $column = $param;
                        $q->orWhere($column, 'LIKE', $search);
                    }
                }
            })
                ->orWhere('status', 'like', "%$search%")
                ->orWhere('service', 'like', "%$search%")
                ->orWhere('code', 'like', "%$search%")
                ->orWhere('type', 'like', "%$search%")
                ->orWhere('title', 'like', "%$search%")
                ->orWhere('message', 'like', "%$search%")
                ->orWhere('number', 'like', "%$search%")
                ->orWhere('meta', 'like', "%$search%")
                ->orWhere('amount', 'like', "%$search%");
        });
    }

    private function relationSearch($query, $relation, $columns, $search)
    {
        foreach (explode(',', $columns) as $column) {
            $query->orWhereHas($relation, function ($q) use ($column, $search) {
                $q->where($column, 'like', "%$search%");
            });
        }

        return $query;
    }
}
