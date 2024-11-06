<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityRequest;
use App\Models\Activity;
use App\Services\Activity\ActivityService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    protected ActivityService $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * @throws \Exception
     */
    public function index(): JsonResponse
    {

        $user = Auth::user();
        $activities = $this->activityService->getUserActivities($user);

        return response()->json($activities);

    }

    public function store(ActivityRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $user = Auth::user();
        $mosque = $user->mosque;

        $activity = $user->activities()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => Carbon::parse($validated['start_date'])->addHours((int) $validated['duration']),
            'mosque_id' => $mosque->id,
        ]);
        $activity->groups()->attach($validated['groups']);

        /// TODO send notification to all student for new activity
        return response()->json([
            'message' => 'new activity added successfully',
        ]);

    }

    public function show(Activity $activity): JsonResponse
    {
        $user = Auth::user();
        $activity = $this->activityService->gerUserActivity($user, $activity->id);

        return response()->json($activity);
    }

    public function cancel(Activity $activity): JsonResponse
    {
        $user = Auth::user();

        $activity =$this->activityService->gerUserActivity($user, $activity->id);

        if ($activity->canceled || $activity->finished) {
            return response()->json([
                'message' => 'activity is already canceled or finished',
            ]);
        }
        $activity->update([
            'canceled' => true,
            'finished' => true,
        ]);

        /// TODO send notification to students that the activity has canceled

        return response()->json([
            'message' => 'activity cancelled successfully',
        ]);

    }
}
