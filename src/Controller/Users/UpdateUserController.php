<?php

namespace App\Controller\Users;

use App\Entity\User;
use App\Form\UserType;
use App\Service\JsonResponseTransformer;
use App\Service\UserService;
use App\Service\ViolationService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use OpenApi\Attributes as OA;

final class UpdateUserController extends AbstractController
{
    public function __construct(
        private readonly JsonResponseTransformer $jsonResponseTransformer,
        private readonly ViolationService $violationService,
        private readonly UserService $userService,
    ) {
    }

    #[OA\Patch(
        description: 'Update particular user',
        summary: 'Update particular user',
        tags: ['User']
    )]
    #[OA\Parameter(
        name: 'user',
        description: 'User id',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid'),
        example: '01948f1c-4b42-7df0-92e1-dc763d765bcc'
    )]
    #[OA\RequestBody(
        required: false,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'firstName', type: 'string', description: 'User first name', example: 'John'),
                new OA\Property(property: 'secondName', type: 'string', description: 'User second name', example: 'Robert', nullable: true),
                new OA\Property(property: 'lastName', type: 'string', description: 'User last name', example: 'Smith'),
                new OA\Property(property: 'email', type: 'string', description: 'User email address', example: 'john.smith@example.com'),
                new OA\Property(property: 'pesel', type: 'string', description: 'User PESEL number', example: '12345678901'),
                new OA\Property(property: 'password', type: 'string', description: 'User password', example: '123Qwerty!')
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'User updated successfully',
        content: new OA\JsonContent(ref: new Model(type: User::class))
    )]
    #[Route('/user/{user}/update', name: 'update_user', methods: ['PATCH'])]
    public function __invoke(Request $request, User $user): JsonResponse|Response
    {
        $form = $this->createForm(UserType::class, $user);

        $updatedData = json_decode($request->getContent(), true);

        $form->submit($updatedData, false);

        if ($form->isValid()) {
            $updatedUser = $this->userService->update($updatedData, $user, $form);

            return $this->jsonResponseTransformer->update($updatedUser, groups: ['user:read']);
        }

        return new JsonResponse([
            'errors' => $this->violationService->getMessages($form->getErrors(true))
        ], 422);
    }
}