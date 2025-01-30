<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class EncryptionService
{
    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
    }

    public function encryptData(string $data): string
    {
        return openssl_encrypt(
            $data,
            $this->parameterBag->get('encryptionCipher'),
            $this->parameterBag->get('encryptionKey'),
            iv: $this->parameterBag->get('encryptionIv')
        );
    }

    public function decryptData(string $encryptedData): string
    {
        return openssl_decrypt(
            $encryptedData,
            $this->parameterBag->get('encryptionCipher'),
            $this->parameterBag->get('encryptionKey'),
            iv: $this->parameterBag->get('encryptionIv')
        );
    }
} 