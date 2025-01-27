<?php

namespace App\Tests\Organization;

use App\Entity\Organization;
use App\Factory\OrganizationFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class DeleteOrganizationTest extends WebTestCase
{
    use ResetDatabase;

    protected KernelBrowser $client;
    protected EntityManager $entityManager;

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

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $createdOrganization = $this->entityManager
            ->getRepository(Organization::class)
            ->find($organization->getId());

        $this->assertNull($createdOrganization);

        //returns error during deleting not existed organization
        $this->client->request('delete', "/api/organization/{$organization->getId()}");

        $this->assertEquals(404, $this->client->getResponse()->getStatusCode());
    }
}
