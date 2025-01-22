<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class JsonResponseTransformer
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    public function create(object $data, int $status = 201, array $headers = []): Response
    {
        return new Response(
            $this->serializer->serialize($data, JsonEncoder::FORMAT),
            $status,
            array_merge($headers, ['Content-Type' => 'application/json;charset=UTF-8'])
        );
    }
}