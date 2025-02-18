<?php

namespace App\Tests\User;

use App\Entity\User;
use App\Tests\Helpers\OrganizationHelper;
use App\Tests\Helpers\UserHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class DeleteUserTest extends WebTestCase
{
    use ResetDatabase;

    private KernelBrowser $client;
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testDeletesUser(): void
    {
        $organization = OrganizationHelper::create();
        $user = UserHelper::create($organization);
        $userRepository = $this->entityManager
            ->getRepository(User::class);

        $createdUser = $userRepository
            ->find($user->getId());

        $this->assertEquals($user->getId(), $createdUser->getId());
        $this->assertEquals($user->getFirstName(), $createdUser->getFirstName());
        $this->assertEquals($user->getLastName(), $createdUser->getLastName());

        $this->client->request('delete', "/api/user/{$user->getId()->toString()}");

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());

        $deletedUser = $userRepository
            ->find($user->getId());

        $this->assertNull($deletedUser);
    }
}
