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
    ) {
    }

    public function create(FormInterface $form): User
    {
        $user = $form->getData();
        
        $this->processUserData($user, $user->getPassword(), $user->getPesel());
        $this->processOrganizations($user, $form->get('organizationIds')->getData());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function update(array $updatedData, User $user, FormInterface $form): User 
    {
        if (isset($updatedData['password']) || isset($updatedData['pesel'])) {
            $this->processUserData(
                $user,
                $updatedData['password'] ? $form->get('password')->getData() : null,
                $updatedData['pesel'] ? $form->get('pesel')->getData() : null
            );
        }

        if (isset($updatedData['organizationIds'])) {
            $this->processOrganizations($user, $form->get('organizationIds')->getData(), true);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    private function processUserData(User $user, ?string $password = null, ?string $pesel = null): void
    {
        if ($password !== null) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        }

        if ($pesel !== null) {
            $user->setPesel($this->encryptionService->encryptData($pesel));
        }
    }

    private function processOrganizations(User $user, array $organizationIds, bool $clearExisting = false): void
    {
        if ($clearExisting) {
            $user->getOrganizations()->clear();
        }

        foreach ($organizationIds as $organizationId) {
            $organization = $this->entityManager->getReference(Organization::class, $organizationId);
            $user->addOrganization($organization);
        }
    }
}