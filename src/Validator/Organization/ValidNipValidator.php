<?php

namespace App\Validator\Organization;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class ValidNipValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (preg_match('/[^0-9\s-]/', $value)) {
            $this->context->buildViolation($constraint->invalidCharactersMessage)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }

        $nip = preg_replace('/[^0-9]/', '', $value);

        if (strlen($nip) !== 10) {
            $this->context->buildViolation($constraint->invalidLengthMessage)
                ->setParameter('{{ value }}', $value)
                ->addViolation();

            return;
        }

        $weights = [6, 5, 7, 2, 3, 4, 5, 6, 7];
        $sum = 0;
        
        for ($i = 0; $i < 9; $i++) {
            $sum += $weights[$i] * intval($nip[$i]);
        }
        
        $checksum = $sum % 11;

        if ($checksum === 10) {
            $checksum = 0;
        }

        if ($checksum !== intval($nip[9])) {
            $this->context->buildViolation($constraint->invalidChecksumMessage)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
