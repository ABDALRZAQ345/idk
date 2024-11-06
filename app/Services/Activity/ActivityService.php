<?php

namespace App\Services\Activity;

use App\Models\User;

class ActivityService
{
    /**
     * @throws \Exception
     */
    public function getUserActivities($user)
    {

        $mosque = $user->mosque;
        if ($user->hasPermission('activity.read') && $mosque) {
            $activities = $mosque->activities()->orderby('start_date')->paginate(10);
        } elseif ($user->group) {
            $activities = $user->group->activities()->orderby('start_date')->paginate(10);
        } else {
            $activities = EmptyPagination();
        }

        return $activities;
    }

    public function gerUserActivity(User $user, $activity_id)
    {
        $mosque = $user->mosque;
        if ($user->hasPermission('activity.read') && $user->mosque) {
            $activity = $mosque->activities()->with('groups')->FindOrFail($activity_id);
        } elseif ($user->group) {
            $activity = $user->group->activities()->FindOrFail($activity_id);
        } else {
            $activity = null;
        }

        return $activity;

    }
}
