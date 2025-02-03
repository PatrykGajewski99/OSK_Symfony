<?php

namespace App\Tests\User;

use App\Tests\Helpers\OrganizationHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class CreateUserTest extends WebTestCase
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

    public function testCreatesUser(): void
    {
        $firstOrganization = OrganizationHelper::create();
        $secondOrganization = OrganizationHelper::create();

        $data = [
            'email'     => 'test@example.com',
            'password'  => 'SoonDofsf56!%^#',
            'firstName' => 'John',
            'lastName'  => 'Doe',
            'pesel'     => '51111584787',
            'organizationIds' => [
                $firstOrganization->getId(),
                $secondOrganization->getId()
            ]
        ];

        $this->client->request('POST', 'api/user/create', content: json_encode($data));

        $response = $this->client->getResponse();

        $userData = json_decode($response->getContent());

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('test@example.com', $userData->email);
        $this->assertEquals('John', $userData->firstName);
        $this->assertEquals('Doe', $userData->lastName);

        $organizationsCollection = new ArrayCollection($userData->organizations);
        $organizationIds = $organizationsCollection->map(fn ($organization) => $organization->id)->toArray();

        $this->assertCount(2, $organizationIds);
        $this->assertContains((string) $firstOrganization->getId(), $organizationIds);
        $this->assertContains((string) $secondOrganization->getId(), $organizationIds);
    }

    public function testCanNotCreteUserForNotExistingOrganization(): void
    {
        $firstOrganization = OrganizationHelper::create();
        $secondOrganization = OrganizationHelper::create();

        $data = [
            'email'     => 'test@example.com',
            'password'  => 'SoonDofsf56!%^#',
            'firstName' => 'John',
            'lastName'  => 'Doe',
            'pesel'     => '51111584787',
            'organizationIds' => [
                $firstOrganization->getId(),
                $secondOrganization->getId(),
                '0194b8cb-c3e6-7724-a8ca-018555716d92'
            ]
        ];

        $this->client->request('POST', 'api/user/create', content: json_encode($data));

        $response = $this->client->getResponse();

        $this->assertEquals(422, $response->getStatusCode());
    }

    public function testCanNotCreteUserWithInvalidPesel(): void
    {
        $firstOrganization = OrganizationHelper::create();
        $secondOrganization = OrganizationHelper::create();

        $data = [
            'email'     => 'test@example.com',
            'password'  => 'SoonDofsf56!%^#',
            'firstName' => 'John',
            'lastName'  => 'Doe',
            'pesel'     => '5111158487',
            'organizationIds' => [
                $firstOrganization->getId(),
                $secondOrganization->getId(),
            ]
        ];

        $this->client->request('POST', 'api/user/create', content: json_encode($data));

        $response = $this->client->getResponse();

        $this->assertEquals(422, $response->getStatusCode());
    }
}
