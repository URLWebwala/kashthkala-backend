<?php

namespace App\Enums;

class StatusEnum
{
    const ACTIVE   = 1;

    const INACTIVE = 2;

    const STATUSES = [
        self::ACTIVE   => 'Active',
        self::INACTIVE => 'Inactive',
    ];
}
