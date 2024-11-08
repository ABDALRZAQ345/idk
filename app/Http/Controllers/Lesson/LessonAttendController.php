<?php

namespace App\Http\Controllers\Lesson;

use App\Http\Controllers\Controller;
use App\Http\Requests\LessonAttendRequest;
use App\Http\Resources\StudentLessonResource;
use App\Models\Lesson;
use App\Services\Lesson\LessonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LessonAttendController extends Controller
{
    protected LessonService $lessonService;

    public function __construct(LessonService $lessonService)
    {
    $this->lessonService = $lessonService;
    }

    //
    public function index(Lesson $lesson): JsonResponse
    {
        $user= Auth::user();
        $lesson=$user->mosque->lessons()->FindOrFail($lesson->id);
        $groups = $lesson->groups->pluck('id');
        $mosque = $lesson->mosque;
        $students = $mosque->students()->whereRelation('groups', function ($q) use ($groups) {
            $q->wherein('groups.id', $groups);
        })->select('students.id', 'students.name')->get();

        return response()->json([
            'students' => StudentLessonResource::collection(
                $students->map(fn ($student) => new StudentLessonResource($student, $lesson))
            ),
        ]);

    }

    public function store(LessonAttendRequest $request, Lesson $lesson): \Illuminate\Http\JsonResponse
    {
        $user= Auth::user();
        $lesson=$user->mosque->lessons()->FindOrFail($lesson->id);
        $request->merge(['activity' => $lesson]);
        $validated = $request->validated();

        $this->lessonService->AssignStudents($lesson, $validated['students']);

        return response()->json([
            'message' => 'attends added successfully',
        ]);
    }
}
