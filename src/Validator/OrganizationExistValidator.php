<?php

namespace App\Validator;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class OrganizationExistValidator extends ConstraintValidator
{
    public function __construct(private readonly OrganizationRepository $organizationRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        $organizationIdsCollection = new ArrayCollection($value);

        $organizationIdsCollection->map(function (string $organizationId) use ($constraint) {
           if (null === $this->organizationRepository->find($organizationId)) {
               $this->context->buildViolation($constraint->notExistingOrganization)
                   ->setParameter('{{ id }}', $organizationId)
                   ->addViolation();
           }
        });
    }
}
