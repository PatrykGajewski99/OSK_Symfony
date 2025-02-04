<?php

namespace App\Controller\Users;

use App\Entity\Organization;
use App\Entity\User;
use App\Service\JsonResponseTransformer;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use \Symfony\Component\HttpFoundation\Response;

final class GetUserController extends AbstractController
{
    public function __construct(private readonly JsonResponseTransformer $jsonResponseTransformer)
    {
    }

    #[OA\Get(
        description: 'Get particular user',
        summary: 'Get particular user',
        tags: ['User']
    )]
    #[OA\Parameter(
        name: 'user',
        description: 'User ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid'),
        example: '01948f1c-4b42-7df0-92e1-dc763d765bcc'
    )]
    #[OA\Response(
        response: 200,
        description: 'Successful response with user details',
        content: new OA\JsonContent(
            ref: new Model(type: User::class)
        )
    )]
    #[Route('/user/{user}', name: 'get_user', methods: ['GET'])]
    public function __invoke(User $user): Response
    {
        return $this->jsonResponseTransformer->get($user, groups: ['user:read']);
    }
}