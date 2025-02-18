<?php

namespace App\Tests\User;

use App\Entity\Organization;
use App\Entity\User;
use App\Tests\Helpers\OrganizationHelper;
use App\Tests\Helpers\UserHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class UpdateUserTest extends WebTestCase
{
    use ResetDatabase;

    private KernelBrowser $client;
    private EntityManager $entityManager;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();

        $firstOrganization = OrganizationHelper::create();

        $this->user = UserHelper::create($firstOrganization);
    }

    public function testUpdateUserData(): void
    {
        $secondOrganization = OrganizationHelper::create();
        $thirdOrganization = OrganizationHelper::create();

        $data = [
            'organizationIds' => [
                $thirdOrganization->getId(),
                $secondOrganization->getId()
            ],
            'firstName' => 'Updated'
        ];

        $this->assertNotEquals($this->user->getFirstName(), 'Updated');
        $this->assertCount(1, $this->user->getOrganizations());

        $this->client->request('PATCH', "api/user/{$this->user->getId()->toString()}/update", content: json_encode($data));

        $response = $this->client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());

        $this->entityManager->clear();

        $refreshedUser = $this->entityManager->find(User::class, $this->user->getId());

        $this->assertEquals('Updated', $refreshedUser->getFirstName());
        $this->assertCount(2, $refreshedUser->getOrganizations());
        
        $organizationIds = array_map(
            fn (Organization $organization) => $organization->getId()->toString(),
            $refreshedUser->getOrganizations()->toArray()
        );
        
        $this->assertContains($secondOrganization->getId()->toString(), $organizationIds);
        $this->assertContains($thirdOrganization->getId()->toString(), $organizationIds);
    }

    public function testReturnsErrorBecauseOfIncorrectPesel(): void
    {
        $data = [
            'firstName' => 'Updated',
            'pesel'     => '123456789',
        ];

        $originalFirstName = $this->user->getFirstName();
        $this->assertCount(1, $this->user->getOrganizations());

        $this->client->request('PATCH', "api/user/{$this->user->getId()->toString()}/update", content: json_encode($data));

        $response = $this->client->getResponse();
        $this->assertEquals(422, $response->getStatusCode());

        $this->entityManager->clear();

        $refreshedUser = $this->entityManager->find(User::class, $this->user->getId());

        $this->assertEquals($originalFirstName, $refreshedUser->getFirstName());
    }
}
