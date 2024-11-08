<?php

namespace App\Http\Controllers\Lesson;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\LessonRequest;
use App\Models\Lesson;
use App\Services\Lesson\LessonService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    protected LessonService $lessonService;
    public function __construct(LessonService $lessonService){
        $this->lessonService = $lessonService;
    }
    public function store(LessonRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = Auth::user();
        $mosque = $user->mosque;

        $lesson=$user->lessons()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'mosque_id' => $mosque->id,
            'start_date' => $validated['start_date'],
        ]);

        $lesson->groups()->attach($validated['groups']);

        /// TODO send notification to all student for new lesson
        return response()->json([
            'message' => 'new lesson added successfully',
        ]);
    }

    /**
     * @throws \Exception
     */
    public function index(Request $request): JsonResponse
    {

        $user=Auth::user();
        $lessons=$this->lessonService->getUserLessons($user);

        if ($request->has('filter.canceled')) {
            if ($request->boolean('filter.canceled')) {
                $lessons = $lessons->where('canceled',  true);
            } else {
                $lessons = $lessons->where('canceled', false);
            }
        }

        $lessons = $lessons->orderBy('start_date')->paginate(20);
        return response()->json($lessons);

    }
    public function show(Lesson $lesson): JsonResponse
    {
        $user = Auth::user();
        $lesson = $this->lessonService->gerUserLesson($user, $lesson->id);

        return response()->json($lesson);
    }

    /**
     * @throws NotFoundException
     */
    public function cancel(Lesson $lesson): JsonResponse
    {
        $user = Auth::user();
        $lesson = $this->lessonService->gerUserLesson($user, $lesson->id);
        if($lesson->canceled ){
            return response()->json([
                'message' => 'lesson  is already canceled or finished',
            ]);
        }
        $lesson->update(['canceled' => true]);
        /// TODO send notification to students that the activity has canceled
        return response()->json([
            'message' => 'lesson cancelled successfully',
        ]);
    }
}
