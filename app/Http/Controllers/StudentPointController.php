<?php

namespace App\Http\Controllers;

use App\Exceptions\FORBIDDEN;
use App\Http\Requests\StudentPointRequest;
use App\Models\Point;
use App\Models\Student;
use App\Services\Student\StudentPointService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentPointController extends Controller
{
    protected StudentPointService $studentPointService;

    public function __construct(StudentPointService $studentPointService)
    {
        $this->studentPointService = $studentPointService;

    }
    //

    /**
     * @throws FORBIDDEN
     */
    public function index(Student $student): JsonResponse
    {
        $this->studentPointService->CheckCanAccessStudentPoints($student);
        $points = $student->points()->where('mosque_id', Auth::user()->mosque->id)->paginate(20)->toArray();
        $totalPoints = $student->points()->where('mosque_id', Auth::user()->mosque->id)->sum('points');
        $points['total_points'] = $totalPoints;

        return response()->json([
            $points,
        ]);
    }

    /**
     * @throws FORBIDDEN
     */
    public function store(StudentPointRequest $request, Student $student): JsonResponse
    {

        $this->studentPointService->CheckCanEditStudentPoints($student);
        $validated = $request->validated();
        $mosque_id = Auth::user()->mosque->id;
        if ($validated['sign'] == -1 && $student->getPointsSum($mosque_id) - $validated['points'] < 0) {
            return response()->json([
                'message' => 'you cant delete more than student `s points',
            ], 400);
        }
        $points = $student->points()->create([
            'mosque_id' => $mosque_id,
            'points' => $validated['sign'] * $validated['points'],
            'reason' => $validated['reason'],
        ]);

        return response()->json([
            'message' => 'points created successfully',
        ]);

    }

    /**
     * @throws FORBIDDEN
     */
    public function show(Student $student, Point $point): JsonResponse
    {
        $this->studentPointService->CheckCanAccessStudentPoints($student);
        $point = $student->points()->where('mosque_id', Auth::user()->mosque->id)->findOrFail($point->id);

        return response()->json([
            'point' => $point,
        ]);
    }

    /**
     * @throws FORBIDDEN
     */
    public function delete(Student $student, Point $point): JsonResponse
    {
        $this->studentPointService->CheckCanEditStudentPoints($student);
        $point = $student->points()->findOrFail($point->id);
        $point->delete();

        return response()->json([
            'message' => 'points deleted successfully',
        ]);
    }

    /**
     * @throws FORBIDDEN
     */
    public function update(StudentPointRequest $request, Student $student, Point $point): JsonResponse
    {
        $this->studentPointService->CheckCanEditStudentPoints($student);
        $point = $student->points()->findOrFail($point->id);
        $validated = $request->validated();
        $point->update([
            'points' => $validated['sign'] * $validated['points'],
            'reason' => $validated['reason'],
        ]);

        return response()->json([
            'message' => 'points updated successfully',
        ]);
    }
}
