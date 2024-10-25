<?php

namespace App\Enums;

namespace App\Enums;

enum RoleEnum
{
    const Manager = 'manager';

    const Supervisor = 'supervisor';

    const Reciever = 'reciever';
    // Add other roles as needed

    public static function getAllRoles(): array
    {
        return [
            self::Manager,
            self::Supervisor,
            self::Reciever,
            // Add other roles as needed
        ];
    }
}
