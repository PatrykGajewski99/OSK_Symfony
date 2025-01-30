<?php

namespace App\Controller\Users;

use App\Entity\User;
use App\Form\UserType;
use App\Service\JsonResponseTransformer;
use App\Service\UserService;
use App\Service\ViolationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Attribute\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

final class CreateUserController extends AbstractController
{
    public function __construct(
        private readonly ViolationService $violationService,
        private readonly JsonResponseTransformer $jsonResponseTransformer,
        private readonly UserService $userService,
    )
    {
    }

    #[OA\Post(
        description: 'Create an user for organizations',
        summary: 'Create an user for organizations',
        tags: ['User']
    )]
    #[OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'firstName', description: 'User first name', type: 'string', example: 'John'),
                new OA\Property(property: 'secondName', description: 'User second name', type: 'string', example: 'Robert', nullable: true),
                new OA\Property(property: 'lastName', description: 'User last name', type: 'string', example: 'Smith'),
                new OA\Property(property: 'email', description: 'User email address', type: 'string', example: 'john.smith@example.com'),
                new OA\Property(property: 'pesel', description: 'User PESEL number', type: 'string', example: '12345678901'),
                new OA\Property(property: 'password', description: 'User password', type: 'string', example: '123Qwerty!'),
                new OA\Property(
                    property: 'organizationIds',
                    description: 'Ids of the organisations to which the user will be entitled',
                    type: 'array',
                    items: new OA\Items(
                        type: 'string',
                        example: ['9e15a7a9-ecd9-4346-97a6-bcf2d601fd45', '9e15a7a9-edfb-4439-b242-f7bd9e5de605']
                    ),
                    nullable: true
                )
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'User successfully created',
        content: new OA\JsonContent(ref: new Model(type: User::class))
    )]
    #[Route('/user/create', name: 'create_user', methods: ['Post'])]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): JsonResponse|Response
    {
        $form = $this->createForm(UserType::class);

        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid() && $form->isSubmitted()) {
            $user = $this->userService->create($form);

            return $this->jsonResponseTransformer->create($user, groups: ['user:read']);
        }

        return new JsonResponse([
            'errors' => $this->violationService->getMessages($form->getErrors(true))
        ], 422);
    }
}