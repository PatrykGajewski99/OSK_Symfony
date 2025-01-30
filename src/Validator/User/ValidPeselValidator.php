<?php

namespace App\Validator\User;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

final class ValidPeselValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
       if (strlen($value) !== 11) {
           $this->context->buildViolation($constraint->incorrectNumberOfSigns)
               ->addViolation();
       }

       if (!preg_match('/^[0-9]+$/', $value)) {
           $this->context->buildViolation($constraint->invalidSigns)
               ->addViolation();
       }

        $numbers = str_split($value);
        $lastPeselNumber = (int) array_pop($numbers);

        $numbersCollection = new ArrayCollection($numbers);
        $index = 0;

        $controlSum = $numbersCollection->map(function (string $number) use (&$index) {
            $index += 1;

            return match ($index % 4) {
                1 => (int) $number,
                2 => (int) $number * 3,
                3 => (int) $number * 7,
                0 => (int) $number * 9,
            };
        })->toArray();
        
        $controlSum = array_sum($controlSum);

        $lastControlSumNumber = $controlSum % 10;

        if (10 - $lastControlSumNumber != $lastPeselNumber) {
            $this->context->buildViolation($constraint->incorrectPesel)
                ->addViolation();
        }
    }
}
