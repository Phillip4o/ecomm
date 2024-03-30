<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use App\Entity\Product;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ProductNormalizer implements NormalizerInterface
{
    public function __construct(

        private readonly ObjectNormalizer $normalizer,
    ) {
    }

    /**
     * @param mixed $object
     * @param $format
     * @param array $context
     * @return array
     */
    public function normalize(mixed $object, $format = null, array $context = []): array
    {
        return $this->normalizer->normalize(
            $object,
            null,
            [
                'groups' => ['elastica'],
            ]
        );
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Product;
    }
}
