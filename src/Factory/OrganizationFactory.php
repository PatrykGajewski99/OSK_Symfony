<?php

namespace App\Factory;

use App\Entity\Organization;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class OrganizationFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Organization::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'country'       => self::faker()->century(),
            'houseNumber'   => self::faker()->buildingNumber(),
            'flatNumber'    => self::faker()->buildingNumber(),
            'name'          => self::faker()->name(),
            'nip'           => '1187859152',
            'street'        => self::faker()->streetName(),
        ];
    }
}
