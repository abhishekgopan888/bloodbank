<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemperatureLog extends Model
{
    use HasFactory;

    protected $fillable = ['refrigerator_id', 'temperature', 'recorded_at'];

    protected $casts = [
        'temperature' => 'float',
        'recorded_at' => 'datetime',
    ];

    public function refrigerator()
    {
        return $this->belongsTo(Refrigerator::class);
    }

    public function scopeUnsafe($query)
    {
        return $query->where('temperature', '>', 6);
    }
}

