<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnregisteredUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone_number',
        'role_id',
        'mosque_id',
    ];

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
