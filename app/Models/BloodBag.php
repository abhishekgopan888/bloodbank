<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;

class BloodBag extends Model
{
    use HasFactory;

    const STATUS_AVAILABLE = 'Available';
    const STATUS_RESERVED = 'Reserved';
    const STATUS_DISPATCHED = 'Dispatched';
    const STATUS_EXPIRED = 'Expired';

    protected $fillable = [
        'bag_number',
        'blood_group',
        'donor_name',
        'collection_date',
        'expiry_date',
        'quantity',
        'status',
        'refrigerator_id',
    ];

    protected $casts = [
        'collection_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function refrigerator()
    {
        return $this->belongsTo(Refrigerator::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date ? $this->expiry_date->isPast() : false;
    }

    public function scopeExpiringWithin24Hours($query)
    {
        return $query->whereBetween('expiry_date', [now(), now()->addDay()]);
    }
}

