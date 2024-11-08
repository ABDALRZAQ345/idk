<?php

namespace App\Http\Controllers\Activity;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityAttendRequest;
use App\Http\Resources\StudentActivityResource;
use App\Models\Activity;
use App\Services\Activity\ActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ActivityAttendController extends Controller
{
    protected ActivityService $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    //
    public function index(Activity $activity): JsonResponse
    {
        $user=Auth::user();
        $activity=$user->mosque->activities()->findOrFail($activity->id);
        $groups = $activity->groups->pluck('id');
        $mosque = $activity->mosque;
        $students = $mosque->students()->whereRelation('groups', function ($q) use ($groups) {
            $q->wherein('groups.id', $groups);
        })->select('students.id', 'students.name')->get();

        return response()->json([
            'students' => StudentActivityResource::collection(
                $students->map(fn ($student) => new StudentActivityResource($student, $activity))
            ),
        ]);

    }

    public function store(ActivityAttendRequest $request, Activity $activity): \Illuminate\Http\JsonResponse
    {
        $user=Auth::user();
        $activity=$user->mosque->activities()->FindOrFail($activity->id);

        $request->merge(['activity' => $activity]);
        $validated = $request->validated();

        $this->activityService->AssignStudents($activity, $validated['students']);

        return response()->json([
            'message' => 'attends added successfully',
        ]);
    }
}
