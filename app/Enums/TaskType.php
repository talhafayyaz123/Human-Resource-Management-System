<?php

namespace App\Enums;

class TaskType
{
    const PERSONAL = 'personal';
    const GROUP = 'group';

    const ALL = [self::PERSONAL, self::GROUP];
}
