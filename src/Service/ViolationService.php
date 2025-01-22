<?php

namespace App\Service;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Validator\ConstraintViolationList;

class ViolationService
{
    public function getMessages(ConstraintViolationList|FormErrorIterator $violations): array
    {
        $errors = [];

        if ($violations instanceof ConstraintViolationList) {
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()][] = [
                    'message' => $violation->getMessage(),
                ];
            }
        } else {
            foreach ($violations as $error) {
                $path = $error->getOrigin()?->getName() ?? 'form';
                $errors[$path][] = [
                    'message' => $error->getMessage(),
                ];
            }
        }

        return $errors;
    }
}