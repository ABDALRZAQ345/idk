<?php

namespace App\Observers;

use App\Models\Group;
use Illuminate\Support\Facades\Log;
class GroupObserver
{
    /**
     * Handle the Group "creating" event.
     */
    public function creating(Group $group): void
    {

        $numbers = Group::where('mosque_id', $group->mosque_id)->orderBy('number', 'asc')->pluck('number')->toArray();
        $mex = getMex($numbers);
        $group->number = $mex ;
    }
    /**
     *  Handle the Group created
     */


    /**
     * Handle the Group "updated" event.
     */
    public function updated(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "deleted" event.
     */
    public function deleted(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "restored" event.
     */
    public function restored(Group $group): void
    {
        //
    }

    /**
     * Handle the Group "force deleted" event.
     */
    public function forceDeleted(Group $group): void
    {
        //
    }
}