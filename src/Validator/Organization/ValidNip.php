<?php

namespace App\Validator\Organization;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
final class ValidNip extends Constraint
{
    public string $invalidCharactersMessage = 'The NIP "{{ value }}" contains invalid characters: it can only contain numbers.';
    public string $invalidLengthMessage = 'The NIP must be exactly 10 digits long. Got: {{ value }}';
    public string $invalidChecksumMessage = 'The NIP "{{ value }}" is invalid - checksum verification failed.';

    public function __construct(public string $mode = 'strict', ?array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->invalidCharactersMessage = $invalidCharactersMessage ?? $this->invalidCharactersMessage;
        $this->invalidLengthMessage = $invalidLengthMessage ?? $this->invalidLengthMessage;
        $this->invalidChecksumMessage = $invalidChecksumMessage ?? $this->invalidChecksumMessage;
    }
}
