<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    use HasFactory;

    protected $appends = ['total'];

    protected $fillable = [
        'name',
        'description',
        'days',
        'daily_rate',
    ];

    public function getTotalAttribute()
    {
        return $this->attributes['days'] * $this->attributes['daily_rate'];
    }
}
