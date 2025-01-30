<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

final class OrganizationExist extends Constraint
{
    public string $notExistingOrganization = 'organization.not_exist';

    public function __construct(public string $mode = 'strict', ?array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->notExistingOrganization = $notExistingOrganization ?? $this->notExistingOrganization;
    }
}
