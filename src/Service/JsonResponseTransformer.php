<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class JsonResponseTransformer
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function create(object $data, int $status = 201, array $headers = [], array $groups = []): Response
    {
        return new Response(
            $this->serializer->serialize($data,JsonEncoder::FORMAT, [
                    'circular_reference_handler' => fn ($object) => $object->getId(),
                    AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                    'groups' => $groups,
                ]),
            $status,
            array_merge($headers, ['Content-Type' => 'application/json;charset=UTF-8'])
        );
    }

    public function get(object|array $data, int $status = 200, array $headers = [], array $groups = []): Response
    {
        return new Response(
            $this->serializer->serialize($data, JsonEncoder::FORMAT, [
                'circular_reference_handler' => fn ($object) => $object->getId(),
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                'groups' => $groups,
            ]),
            $status,
            array_merge($headers, ['Content-Type' => 'application/json;charset=UTF-8'])
        );
    }

    public function update(object $data, int $status = 200, array $headers = [], array $groups = []): Response
    {
        return new Response(
            $this->serializer->serialize($data, JsonEncoder::FORMAT, [
                'circular_reference_handler' => fn ($object) => $object->getId(),
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                'groups' => $groups,
            ]),
            $status,
            array_merge($headers, ['Content-Type' => 'application/json;charset=UTF-8'])
        );
    }
}