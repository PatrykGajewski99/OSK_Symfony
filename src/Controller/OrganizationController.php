<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Form\OrganizationType;
use App\Service\JsonResponseTransformer;
use App\Service\ViolationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

final class OrganizationController extends AbstractController
{
    public function __construct(private readonly ViolationService $violationService, private readonly JsonResponseTransformer $jsonResponseTransformer)
    {
    }

    #[OA\Post(
        path: '/api/organization/create',
        description: 'Creates a new organization',
        summary: 'Create new organization',
        tags: ['Organization']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: new Model(type: Organization::class))
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

            return $this->jsonResponseTransformer->create($organization);
        }

        return new JsonResponse([
            'errors' => $this->violationService->getMessages($form->getErrors(true))
        ], 422);
    }
}
