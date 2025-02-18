<?php

namespace App\Controller\Organizations;

use App\Entity\Organization;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;

final class DeleteOrganizationController extends AbstractController
{
    #[OA\Delete(
        description: 'Delete particular organization',
        summary: 'Delete particular organization',
        tags: ['Organization']
    )]
    #[OA\Parameter(
        name: 'organization',
        description: 'Organization ID',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid'),
        example: '01948f1c-4b42-7df0-92e1-dc763d765bcc'
    )]
    #[OA\Response(
        response: 200,
        description: 'Organization deleted successfully',
    )]
    #[Route(path: 'organization/{organization}', name: 'delete_organization', methods: ['DELETE'])]
    public function __invoke(Organization $organization, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($organization);

        $entityManager->flush();

        return new JsonResponse([], 204);
    }
}