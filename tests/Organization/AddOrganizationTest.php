<?php

namespace App\Tests\Organization;

use App\Entity\Organization;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Zenstruck\Foundry\Test\ResetDatabase;

class AddOrganizationTest extends WebTestCase
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

    public function testCreatesOrganizationSuccessfully(): void
    {
        $data = [
            'name'          => 'Aos Company',
            'street'        => 'Loyals street',
            'houseNumber'   => '12',
            'nip'           => '3943851138',
            'country'       => 'Poland',
        ];

        $this->client->request('POST', '/api/organization/create', content: json_encode($data));

        $this->assertEquals(201, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('id', $responseContent);
        $this->assertArrayHasKey('name', $responseContent);
        $this->assertEquals($data['name'], $responseContent['name']);
        $this->assertEquals($data['nip'], $responseContent['nip']);

        $organization = $this->entityManager
            ->getRepository(Organization::class)
            ->find($responseContent['id']);

        $this->assertNotNull($organization);
        $this->assertEquals($data['name'], $organization->getName());
        $this->assertEquals($data['country'], $organization->getCountry());
    }

    public function testCreateOrganizationWithInvalidData(): void
    {
        $data = [
            'name'          => 'Aos Company',
            'street'        => 'Loyals street',
            'houseNumber'   => '12',
            'nip'           => '12312312311',
            'country'       => 'Poland',
        ];

        $this->client->request('POST', '/api/organization/create', content: json_encode($data));

        $this->assertEquals(422, $this->client->getResponse()->getStatusCode());

        $responseContent = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('errors', $responseContent);

        $organization = $this->entityManager
            ->getRepository(Organization::class)
            ->findBy(['nip' => $data['nip']]);

        $this->assertEmpty($organization);
    }
}
