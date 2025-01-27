<?php

namespace App\Tests\Organization;

use App\Factory\OrganizationFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class GetOrganizationTest extends WebTestCase
{
    use ResetDatabase;

    protected KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testGetParticularOrganization(): void
    {
        $organization = OrganizationFactory::createOne();

        $this->client->request('get', "api/organization/{$organization->getId()}");

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertEquals((string) $organization->getId(), $responseContent['id']);
        $this->assertEquals($organization->getName(), $responseContent['name']);
        $this->assertEquals($organization->getCountry(), $responseContent['country']);
    }
}
