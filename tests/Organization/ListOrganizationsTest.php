<?php

namespace App\Tests\Organization;

use App\Factory\OrganizationFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class ListOrganizationsTest extends WebTestCase
{
    use ResetDatabase;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testListsOrganizations(): void
    {
        $firstOrganization = OrganizationFactory::createOne();
        $secondOrganization = OrganizationFactory::createOne();
        $thirdOrganization = OrganizationFactory::createOne();
        $fourthOrganization = OrganizationFactory::createOne();
        $fifthOrganization = OrganizationFactory::createOne();

        $this->client->request('get', 'api/organizations');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $organizationIds = array_map(fn (array $item) => $item['id'], $responseContent);

        $this->assertContains((string) $firstOrganization->getId(), $organizationIds);
        $this->assertContains((string) $secondOrganization->getId(), $organizationIds);
        $this->assertContains((string) $thirdOrganization->getId(), $organizationIds);
        $this->assertContains((string) $fourthOrganization->getId(), $organizationIds);
        $this->assertContains((string) $fifthOrganization->getId(), $organizationIds);
    }
}
