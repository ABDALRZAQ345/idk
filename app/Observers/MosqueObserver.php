<?php

namespace App\Observers;

use App\Enums\RoleEnum;
use App\Models\Mosque;
use App\Models\Permission;

class MosqueObserver
{
    /**
     * Handle the Mosque "created" event.
     */
    public function created(Mosque $mosque): void
    {

        $roles = RoleEnum::getAllRoles();
        foreach ($roles as $role) {
            $role = $mosque->roles()->create(['name' => $role]);
            $p = Permission::all();
            foreach ($p as $permission) {
                $role->assignPermission($permission->name);
            }
        }

    }

    /**
     * Handle the Mosque "updated" event.
     */
    public function updated(Mosque $mosque): void
    {
        //
    }

    /**
     * Handle the Mosque "deleted" event.
     */
    public function deleted(Mosque $mosque): void
    {
        //
    }

    /**
     * Handle the Mosque "restored" event.
     */
    public function restored(Mosque $mosque): void
    {
        //
    }

    /**
     * Handle the Mosque "force deleted" event.
     */
    public function forceDeleted(Mosque $mosque): void
    {
        //
    }
}
