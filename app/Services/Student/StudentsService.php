<?php

namespace App\Services\Student;

use App\Exceptions\FORBIDDEN;
use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class StudentsService
{
    public function getStudents()
    {
        $user = Auth::user();

        if ($user->hasPermission('show_all_students')) {
            $students = $user->mosque->students()->paginate(20);
        } elseif ($user->group) {
            $students = $user->group->students()->paginate(20);
        } else {
            $students = $this->NoStudents();
        }

        return $students;
    }

    /**
     * @throws FORBIDDEN
     */
    public function CheckCanAccessStudent(Student $student): void
    {
        $user = Auth::user();
        $status = false;
        if ($user->hasPermission('show_all_students') && $user->mosque) {
            $status = $user->mosque->students()->where('students.id', $student->id)->exists();
        } elseif ($user->group) {
            $status = $user->group->students()->where('students.id', $student->id)->exists();
        }

        if (! $status) {
            throw new FORBIDDEN('you are not allowed to access this student');
        }

    }

    /**
     * @throws FORBIDDEN
     */
    public function CanDeleteStudent(Student $student): void
    {
        $user = Auth::user();
        $mosque = $user->mosque;
        if (! $user->hasPermission('delete_student') || ! $mosque->students()->where('students.id', $student->id)->exists()) {
            throw new FORBIDDEN('you are not allowed to delete this student');
        }
    }

    /**
     * @return LengthAwarePaginator
     *                              that return an empty collection of students
     */
    public function NoStudents(): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            Collection::make([]), // Empty collection
            0,                    // Total items
            20,                   // Per page
            1,                    // Current page
            ['path' => request()->url(), 'query' => request()->query()] // For consistent pagination links
        );
    }
}
