<?php

namespace App\Controller\Organizations;

use App\Entity\Organization;
use App\Service\JsonResponseTransformer;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class GetOrganizationController extends AbstractController
{
    public function __construct(private readonly JsonResponseTransformer $jsonResponseTransformer)
    {
    }

    #[OA\Get(
        path: '/api/organization/{organization}',
        description: 'Get particular organization',
        summary: 'Get particular organization',
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
        description: 'Successful response with organization details',
        content: new OA\JsonContent(
            ref: new Model(type: Organization::class)
        )
    )]
    #[Route('/organization/{organization}', name: 'get_organization', methods: ['Get'])]
    public function __invoke(Organization $organization): Response
    {
        return $this->jsonResponseTransformer->get($organization);
    }
}
