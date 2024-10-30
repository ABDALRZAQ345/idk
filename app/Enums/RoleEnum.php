<?php

namespace App\Enums;

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
}
