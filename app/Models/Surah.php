<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Surah extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function surah_points(): HasMany
    {
        return $this->hasMany(SurahPoint::class);
    }
}
