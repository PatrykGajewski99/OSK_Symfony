<?php

namespace App\DataFixtures;

use App\Factory\OrganizationFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $organizations = OrganizationFactory::createMany(10);
        
        UserFactory::createMany(20, fn () => [
            'organizations' => [
                $organizations[random_int(0, 4)],
                $organizations[random_int(5, 9)],
            ]
        ]);
    }
}
