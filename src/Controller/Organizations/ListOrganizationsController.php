<?php

namespace App\Controller\Organizations;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use App\Service\JsonResponseTransformer;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class ListOrganizationsController extends AbstractController
{
    public function __construct(
        private readonly JsonResponseTransformer $jsonResponseTransformer,
        private readonly OrganizationRepository $organizationRepository,
    ) {
    }

    #[OA\Get(
        description: 'List existing organizations',
        summary: 'List existing organizations',
        tags: ['Organization']
    )]
    #[OA\Response(
        response: 200,
        description: 'Organizations list',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Organization::class))
        )
    )]
    #[Route('/organizations', name: 'list_organizations', methods: ['GET'])]
    public function __invoke(EntityManagerInterface $entityManager): Response
    {
        $organizations = $this->organizationRepository->findAll();

        return $this->jsonResponseTransformer->get($organizations, groups: ['organization:read'] );
    }
}
