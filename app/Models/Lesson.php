<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Lesson extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function mosque(): BelongsTo
    {
        return $this->belongsTo(Mosque::class);
    }
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }
    public function enrolledStudents(): HasManyThrough //it work so dont touch it please :)
    {
        return $this->hasManyThrough(
            Student::class,         // The final model we want to retrieve
            Attend::class,          // The intermediary model
            'action_id',            // Foreign key on `attends` table (to `Activity`)
            'id',                   // Foreign key on `students` table
            'id',                   // Local key on `activities` table
            'student_id'            // Local key on `attends` table to `students`
        )->where('attends.action_type', Lesson::class); // Apply morph constraint
    }
    public function attends(): MorphMany
    {
        return $this->morphMany(Attend::class, 'action');
    }
}
