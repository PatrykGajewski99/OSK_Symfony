<?php

namespace App\Tests\Organization;

use App\Entity\Organization;
use App\Entity\User;
use App\Factory\OrganizationFactory;
use App\Tests\Helpers\OrganizationHelper;
use App\Tests\Helpers\UserHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class DeleteOrganizationTest extends WebTestCase
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

    public function testDeletesOrganization(): void
    {
        $organization = OrganizationFactory::createOne();

        $createdOrganization = $this->entityManager
            ->getRepository(Organization::class)
            ->find($organization->getId());

        $this->assertNotNull($createdOrganization);

        $this->client->request('delete', "/api/organization/{$organization->getId()}");

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());

        $createdOrganization = $this->entityManager
            ->getRepository(Organization::class)
            ->find($organization->getId());

        $this->assertNull($createdOrganization);

        //returns error during deleting not existed organization
        $this->client->request('delete', "/api/organization/{$organization->getId()}");

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testDeletesOrganizationWithUsers(): void
    {
        $respository = $this->entityManager
            ->getRepository(Organization::class);

        $organization = OrganizationHelper::create();

        $firstUser = UserHelper::create($organization);
        $secondUser = UserHelper::create($organization);
        $thirdUser = UserHelper::create($organization);

        $createdOrganization = $respository
            ->find($organization->getId());

        $this->assertEquals($organization->getId(), $createdOrganization->getId());

        $organizationUserIds = $createdOrganization->getUsers()->map(fn (User $user) => $user->getId()->toString());

        $this->assertContains($firstUser->getId()->toString(), $organizationUserIds);
        $this->assertContains($secondUser->getId()->toString(), $organizationUserIds);
        $this->assertContains($thirdUser->getId()->toString(), $organizationUserIds);

        $this->client->request('delete', "/api/organization/{$organization->getId()}");

        $createdOrganization = $respository
            ->find($organization->getId());

        $createdFirstUser = $respository
            ->find($firstUser->getId());

        $createdSecondUser = $respository
            ->find($secondUser->getId());

        $createdThirdUser = $respository
            ->find($thirdUser->getId());

        $this->assertEquals(204, $this->client->getResponse()->getStatusCode());
        $this->assertNull($createdOrganization);
        $this->assertNull($createdFirstUser);
        $this->assertNull($createdSecondUser);
        $this->assertNull($createdThirdUser);
    }
}
