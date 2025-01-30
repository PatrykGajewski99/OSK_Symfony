<?php

namespace App\Validator\Organization;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_METHOD)]
final class ValidNip extends Constraint
{
    public string $invalidCharactersMessage = 'nip.invalid_characters';
    public string $invalidLengthMessage = 'nip.invalid_length';
    public string $invalidChecksumMessage = 'nip.invalid_checksum';

    public function __construct(public string $mode = 'strict', ?array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);

        $this->invalidCharactersMessage = $invalidCharactersMessage ?? $this->invalidCharactersMessage;
        $this->invalidLengthMessage = $invalidLengthMessage ?? $this->invalidLengthMessage;
        $this->invalidChecksumMessage = $invalidChecksumMessage ?? $this->invalidChecksumMessage;
    }
}
