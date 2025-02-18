<?php

namespace App\ValueObjects;

enum RoleName: string
{
    case ADMIN = 'admin';
    case INSTRUCTOR = 'instructor';
    case TRAINEE = 'trainee';

    public static function getAll(): array
    {
        return [
          self::ADMIN,
          self::INSTRUCTOR,
          self::TRAINEE,
        ];
    }
}
