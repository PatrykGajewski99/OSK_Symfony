<?php

namespace App\Tests\Organization;

use App\Factory\OrganizationFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class UpdateOrganizationTest extends WebTestCase
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

    public function testUpdatesOrganizationData(): void
    {
        $organization = OrganizationFactory::createOne([
            'name'  => 'Random company'
        ]);

        $this->assertEquals('Random company', $organization->getName());

        $this->client->request('PATCH',"/api/organization/{$organization->getId()}/update", content: json_encode([
                'name'         => 'Updated company name',
                'street'       => $organization->getStreet(),
                'houseNumber'  => $organization->getHouseNumber(),
                'flatNumber'   => $organization->getFlatNumber(),
                'nip'          => $organization->getNip(),
                'country'      => $organization->getCountry(),
            ])
        );

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());
        $this->assertEquals('Updated company name', $organization->getName());
    }

    public function testReturnsErrorBecauseOfInvalidNIPValue(): void
    {
        $organization = OrganizationFactory::createOne([
            'nip'  => '1187859152'
        ]);

        $this->assertEquals('1187859152', $organization->getNip());

        $this->client->request('PATCH',"/api/organization/{$organization->getId()}/update", content: json_encode([
                'name'         => $organization->getName(),
                'street'       => $organization->getStreet(),
                'houseNumber'  => $organization->getHouseNumber(),
                'flatNumber'   => $organization->getFlatNumber(),
                'nip'          => '1231231233',
                'country'      => $organization->getCountry(),
            ])
        );

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());
    }
}
