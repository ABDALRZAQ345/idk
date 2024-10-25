<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurahRecitation extends Model
{
    use HasFactory;

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }

    public function surah(): BelongsTo
    {
        return $this->belongsTo(Surah::class);
    }
}
