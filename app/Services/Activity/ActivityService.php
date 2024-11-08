<?php

namespace App\Services\Activity;

use App\Exceptions\NotFoundException;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ActivityService
{
    /**
     * @throws \Exception
     */
    public function getUserActivities($user)
    {

        $mosque = $user->mosque;
        if ($user->hasPermission('activity.read') && $mosque) {
            $activities = $mosque->activities();
        } elseif ($user->group) {
            $group=$user->group;
            $activities = $group->activities();
        } else {
            $activities = Activity::where('id', Activity::max('id') + 1);
        }

        return $activities;
    }

    /**
     * @throws NotFoundException
     */
    public function gerUserActivity(User $user, $activity_id)
    {
        $mosque = $user->mosque;
        if ($user->hasPermission('activity.read') && $user->mosque) {
            $activity = $mosque->activities()->with('groups')->with('enrolledStudents')->FindOrFail($activity_id);
        } elseif ($user->group) {
            $group=$user->group;
            $activity = $group->activities()->with('groups')->with('enrolledStudents')->FindOrFail($activity_id);
        } else {
           throw new NotFoundException();
        }

        return $activity;

    }

    public function AssignStudents(Activity $activity, $students_id): void
    {
        try {
            DB::beginTransaction();
            $activity->attends()->delete();
            foreach ($students_id as $studentId) {
                if ($activity->enrolledStudents()->find($studentId) == null) {
                    $activity->attends()->create([
                        'student_id' => $studentId,
                    ]);
                }

            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }

    }
}
