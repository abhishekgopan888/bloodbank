<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refrigerator extends Model
{
    use HasFactory;

    protected $fillable = ['identifier', 'blood_bank_id', 'status'];

    public function bloodBank()
    {
        return $this->belongsTo(BloodBank::class);
    }

    public function temperatureLogs()
    {
        return $this->hasMany(TemperatureLog::class);
    }

    public function bloodBags()
    {
        return $this->hasMany(BloodBag::class);
    }
}

