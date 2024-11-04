<?php

namespace App\Observers;

use App\Enums\RoleEnum;
use App\Models\Mosque;

class MosqueObserver
{
    /**
     * Handle the Mosque "created" event.
     */
    public function created(Mosque $mosque): void
    {
        // creating the rules for each new mosque
        $roles = RoleEnum::getAllRoles();
        foreach ($roles as $role) {
            $role_name = $role;
            $role = $mosque->roles()->create(['name' => $role]);
            $p = RoleEnum::getPermissions($role_name);
            foreach ($p as $permission) {
                $role->assignPermission($permission['name']);
            }
        }
        for ($i = 1; $i <= 30; $i++) {
            $mosque->surah_points()->create([
                'surah_id' => $i,
            ]);
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
