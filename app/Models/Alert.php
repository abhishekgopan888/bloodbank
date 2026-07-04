<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alert extends Model
{
    use HasFactory;

    protected $fillable = ['refrigerator_id', 'type', 'message', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function refrigerator()
    {
        return $this->belongsTo(Refrigerator::class);
    }
}

