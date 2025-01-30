<?php

namespace App\Controller\Organizations;

use App\Entity\Organization;
use App\Form\OrganizationType;
use App\Service\JsonResponseTransformer;
use App\Service\ViolationService;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class UpdateOrganizationController extends AbstractController
{
    public function __construct(
        private readonly JsonResponseTransformer $jsonResponseTransformer,
        private readonly ViolationService $violationService
    ) {
    }

    #[OA\Patch(
        description: 'Update particular organization',
        summary: 'Update particular organization',
        tags: ['Organization']
    )]
    #[OA\Parameter(
        name: 'organization',
        description: 'Organization id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid'),
        example: '01948f1c-4b42-7df0-92e1-dc763d765bcc'
    )]
    #[OA\RequestBody(
        required: false,
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
        response: 204,
        description: 'Organization updated successfully',
        content: new OA\JsonContent(ref: new Model(type: Organization::class))
    )]
    #[Route('/organization/{organization}/update', name: 'update_organization', methods: ['PATCH'])]
    public function __invoke(Request $request, Organization $organization, EntityManagerInterface $entityManager): JsonResponse|Response
    {
        $form = $this->createForm(OrganizationType::class, $organization);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $entityManager->persist($organization);
            $entityManager->flush();

            return $this->jsonResponseTransformer->update($organization, 201, groups: ['organization:read']);
        }

        return new JsonResponse([
            'errors' => $this->violationService->getMessages($form->getErrors(true))
        ], 422);
    }
}