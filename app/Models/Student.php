<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    public function surah_recitations(): HasMany
    {
        return $this->hasMany(SurahRecitation::class);
    }

    public function section_recitations(): HasMany
    {
        return $this->hasMany(SectionRecitation::class);
    }

    public function pages_recitations(): HasMany
    {
        return $this->hasMany(PageRecitation::class);
    }

    public function points(): HasMany
    {
        return $this->hasMany(Point::class);
    }

    public function mosques(): BelongsToMany
    {
        return $this->belongsToMany(Mosque::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'mosque_student')
            ->withPivot('mosque_id')
            ->withTimestamps();
    }

    public function hasGroup($mosque): bool
    {
        return $this->belongsToMany(Group::class, 'mosque_student')
            ->wherePivot('mosque_id', $mosque)
            ->exists();
    }

    public function confirmation_code(): MorphOne
    {
        return $this->morphOne(ConfirmationCode::class, 'confirmable');
    }
}
