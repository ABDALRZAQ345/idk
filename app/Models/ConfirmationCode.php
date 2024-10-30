<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmationCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'expires_at',
    ];

    public function confirmable()
    {
        return $this->morphTo();
    }
}
