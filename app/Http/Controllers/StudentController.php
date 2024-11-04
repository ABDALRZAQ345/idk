<?php

namespace App\Http\Controllers;

use App\Exceptions\FORBIDDEN;
use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Services\Student\StudentsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    protected StudentsService $studentsService;

    public function __construct(StudentsService $studentsService)
    {
        $this->studentsService = $studentsService;
    }

    //
    public function index(): JsonResponse
    {
        $students = $this->studentsService->getStudents();

        return response()->json([
            $students,
        ]);
    }

    /**
     * @throws FORBIDDEN
     */
    public function show(Student $student): JsonResponse
    {

        $this->studentsService->CheckCanAccessStudent($student);
        $mosque_id = Auth::user()->mosque->id;

        return response()->json([
            'student' => new StudentResource($student, $mosque_id),
        ]);
    }

    /**
     * @throws FORBIDDEN
     */
    public function delete(Student $student): JsonResponse
    {

        $this->studentsService->CheckCanAccessStudent($student);
        $this->studentsService->CanDeleteStudent($student);
        $user = Auth::user();
        $user->mosque->students()->detach($student);

        return response()->json([
            'message' => 'student deleted successfully from the mosque ',
        ]);
    }
}
