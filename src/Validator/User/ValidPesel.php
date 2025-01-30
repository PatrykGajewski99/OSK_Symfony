<?php

namespace App\Validator\User;

use Symfony\Component\Validator\Constraint;

final class ValidPesel extends Constraint
{
    public string $invalidSigns = 'pesel.invalid_signs';
    public string $incorrectPesel = 'pesel.incorrect_pesel';
    public string $incorrectNumberOfSigns = 'pesel.incorrect_number_of_signs';

    public function __construct(public string $mode = 'strict', ?array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->invalidSigns = $invalidSigns ?? $this->invalidSigns;
        $this->incorrectPesel = $incorrectPesel ?? $this->incorrectPesel;
        $this->incorrectNumberOfSigns = $incorrectNumberOfSigns ?? $this->incorrectNumberOfSigns;
    }
}
