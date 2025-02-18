<?php

namespace App\Controller\Users;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class DeleteUserController extends AbstractController
{
    #[OA\Delete(
        description: 'Delete particular user',
        summary: 'Delete particular user',
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
        description: 'User deleted successfully',
    )]
    #[Route(path: 'user/{user}', name: 'delete_user', methods: ['DELETE'])]
    public function __invoke(Request $request, User $user, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($user);

        $entityManager->flush();

        return new JsonResponse([], 204);
    }
}