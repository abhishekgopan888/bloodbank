<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BloodBank extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location'];

    public function refrigerators()
    {
        return $this->hasMany(Refrigerator::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

