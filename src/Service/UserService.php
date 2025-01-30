<?php

namespace App\Service;

use App\Entity\Organization;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly EncryptionService $encryptionService,
    )
    {
    }

    public function create(FormInterface $form): User
    {
        $user = $form->getData();

        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPassword());
        $encryptedPesel = $this->encryptionService->encryptData($user->getPesel());

        $user->setPassword($hashedPassword);
        $user->setPesel($encryptedPesel);

        $organizationIds = $form->get('organizationIds')->getData();

        foreach ($organizationIds as $organizationId) {
            $organization = $this->entityManager->getReference(Organization::class, $organizationId);

            $user->addOrganization($organization);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}