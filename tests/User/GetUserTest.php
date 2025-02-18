<?php

namespace App\Tests\User;

use App\Tests\Helpers\OrganizationHelper;
use App\Tests\Helpers\UserHelper;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class GetUserTest extends WebTestCase
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

    public function testGetsUser(): void
    {
       $organization = OrganizationHelper::create();

       $user = UserHelper::create();

       $this->client->request('GET', "/api/user/{$user->getId()}");

       $response = $this->client->getResponse();
       $data = json_decode($response->getContent());

       $this->assertEquals(200, $response->getStatusCode());
       $this->assertEquals($user->getFirstName(), $data->firstName);
       $this->assertEquals($user->getLastName(), $data->lastName);
       $this->assertEquals($user->getEmail(), $data->email);
       $this->assertEmpty($data->organizations);

       $secondUser = UserHelper::create($organization);

       $this->client->request('GET', "/api/user/{$secondUser->getId()}");

       $response = $this->client->getResponse();
       $data = json_decode($response->getContent());

       $this->assertEquals(200, $response->getStatusCode());
       $this->assertEquals($secondUser->getFirstName(), $data->firstName);
       $this->assertEquals($secondUser->getLastName(), $data->lastName);
       $this->assertEquals($secondUser->getEmail(), $data->email);
       $this->assertCount(1, $data->organizations);
    }
}
