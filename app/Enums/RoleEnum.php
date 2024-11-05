<?php

namespace App\Enums;

use App\Models\Permission;

enum RoleEnum
{
    const Manager = 'manager';

    const Supervisor = 'supervisor';

    const Receiver = 'receiver';

    // Add other roles as needed

    public static function getAllRoles(): array
    {
        return [
            self::Manager,
            self::Supervisor,
            self::Receiver,
            // Add other roles as needed
        ];
    }

    public static function getPermissions($role): array
    {

        if ($role == self::Manager) {
            return Permission::all()->select('name')->toArray();
        } elseif ($role == self::Supervisor) {
            return Permission::all()->whereNotIn('name', [
                'receiver.update',
                'receiver.read',
                'receiver.delete',
                'receiver.store',
                'students.store',
                'students.delete',
                'supervisor.read',
                'points.delete',
                'points.update',
            ])->select('name')->toArray();
        } elseif ($role == self::Receiver) {
            return Permission::all()->whereIn('name', [
                'recitation.store',
                'recitation.update',
                'recitation.read',
                'recitation.delete',
                'groups.read',
                'student_points.read',
                'group_students.read',
                'activity.read'
            ])->select('name')->toArray();
        } else {
            return [];
        }
    }
}
