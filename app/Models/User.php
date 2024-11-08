<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];

    public function group(): HasOne
    {
        return $this->hasOne(Group::class);
    }

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permission)
    {

        if ($this->role) {
            return $this->permissions()->where('name', $permission)->exists();
        }

        return false;
    }

    public function permissions()
    {
        return $this->role->permissions();
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}

