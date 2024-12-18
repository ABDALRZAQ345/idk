<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageRecitation extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function points(): \Illuminate\Database\Eloquent\Relations\MorphOne
    {
        return $this->morphOne(Point::class, 'recitation');
    }
}
