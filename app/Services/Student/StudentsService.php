<?php

namespace App\Services\Student;

use App\Exceptions\FORBIDDEN;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentsService
{
    public function getStudents()
    {
        $user = Auth::user();
        $mosque_id = $user->mosque->id;
        if ($user->hasPermission('students.read')) {
            $students = $user->mosque->students()->WithPointsSum($mosque_id)->paginate(20);
        } elseif ($user->group) {
            $students = $user->group->students()->WithPointsSum($mosque_id)->paginate(20);
        } else {
            $students = EmptyPagination();
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
        if ($user->hasPermission('students.read') && $user->mosque) {
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
        if (! $user->hasPermission('student.delete') || ! $mosque->students()->where('students.id', $student->id)->exists()) {
            throw new FORBIDDEN('you are not allowed to delete this student');
        }
    }
}
