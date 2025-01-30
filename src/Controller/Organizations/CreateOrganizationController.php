<?php

namespace App\Controller\Organizations;

use App\Entity\Organization;
use App\Form\OrganizationType;
use App\Service\JsonResponseTransformer;
use App\Service\ViolationService;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CreateOrganizationController extends AbstractController
{
    public function __construct(private readonly ViolationService $violationService, private readonly JsonResponseTransformer $jsonResponseTransformer)
    {
    }

    #[OA\Post(
        description: 'Creates a new organization',
        summary: 'Create new organization',
        tags: ['Organization']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'name', type: 'string', description: 'Name of the organization', example: 'Example Organization Ltd.'),
                new OA\Property(property: 'street', type: 'string', description: 'Street name', example: 'Main Street'),
                new OA\Property(property: 'houseNumber', type: 'string', description: 'House number', example: '123'),
                new OA\Property(property: 'flatNumber', type: 'string', description: 'Flat number (optional)', example: '45', nullable: true),
                new OA\Property(property: 'nip', type: 'string', description: 'Tax identification number', example: '1234567890'),
                new OA\Property(property: 'country', type: 'string', description: 'Country name', example: 'Poland')
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Organization successfully created',
        content: new OA\JsonContent(ref: new Model(type: Organization::class))
    )]
    #[Route('/organization/create', name: 'create_organization', methods: ['POST'])]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse|Response
    {
        $form = $this->createForm(OrganizationType::class);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $organization = $form->getData();
            
            $entityManager->persist($organization);
            $entityManager->flush();

            return $this->jsonResponseTransformer->create($organization, groups: ['organization:read']);
        }
;
        return new JsonResponse([
            'errors' => $this->violationService->getMessages($form->getErrors(true))
        ], 422);
    }
}
