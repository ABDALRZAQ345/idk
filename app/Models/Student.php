<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = ['pivot', 'remember_token'];

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

    public function group($mosque_id)
    {
        return $this->belongsToMany(Group::class, 'mosque_student')
            ->wherePivot('mosque_id', $mosque_id)->select(['groups.name', 'groups.id', 'groups.number'])->first();
    }

    public function scopeWithPointsSum(Builder $query, $mosque_id): void
    {
        $query->withSum(['points as points_sum' => function ($query) use ($mosque_id) {
            $query->where('mosque_id', $mosque_id);
        }], 'points')->orderByDesc('points_sum');
    }

    public function getPointsSum($mosque_id)
    {
        return $this->points()->where('mosque_id', $mosque_id)->sum('points');
    }
}
