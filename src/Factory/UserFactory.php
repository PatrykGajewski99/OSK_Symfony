<?php

namespace App\Factory;

use App\Entity\User;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class UserFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return User::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'email'     => self::faker()->email(),
            'firstName' => self::faker()->firstName(),
            'lastName'  => self::faker()->lastName(),
            'password'  => self::faker()->password(),
            'pesel'     => '51111584787',
        ];
    }
}
