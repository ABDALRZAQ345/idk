<?php

namespace App\Services\Student;

use App\Exceptions\FORBIDDEN;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class StudentPointService
{
    protected StudentsService $studentsService;

    public function __construct(StudentsService $studentsService)
    {
        $this->studentsService = $studentsService;

    }

    /**
     * @throws FORBIDDEN
     */
    public function CheckCanEditStudentPoints(Student $student): void
    {
        $this->studentsService->CheckCanAccessStudent($student);
        $user = Auth::user();
        if (! $user->hasPermission('student_points.update')) {
            throw new FORBIDDEN('you can not access student points');
        }

    }

    /**
     * @throws FORBIDDEN
     */
    public function CheckCanAccessStudentPoints(Student $student): void
    {
        $this->studentsService->CheckCanAccessStudent($student);
        $user = Auth::user();
        if (! $user->hasPermission('student_points.read')) {
            throw new FORBIDDEN('you can not access student points');
        }
    }
}
