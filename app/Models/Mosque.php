<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mosque extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['name', 'location'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }

    public function points(): HasMany
    {
        return $this->hasMany(Point::class);
    }

    public function surah_recitations(): HasMany
    {
        return $this->hasMany(SurahRecitation::class);
    }

    public function section_recitations(): HasMany
    {
        return $this->hasMany(SectionRecitation::class);
    }

    public function page_recitations(): HasMany
    {
        return $this->hasMany(PageRecitation::class);
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
