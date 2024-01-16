<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle\Serializer;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;
use Williarin\WordpressInterop\Serializer\SerializedArrayDenormalizer;

class Serializer extends SymfonySerializer
{
    public function __construct(
        private array $normalizers = [],
        array $encoders = [],
    ) {
        $objectNormalizer = new ObjectNormalizer(
            new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader())),
            new CamelCaseToSnakeCaseNameConverter(),
            null,
            new ReflectionExtractor()
        );

        $this->normalizers = [
            new DateTimeNormalizer(),
            new ArrayDenormalizer(),
            new SerializedArrayDenormalizer($objectNormalizer),
            $objectNormalizer,
        ];

        parent::__construct($this->normalizers, $encoders);
    }
}
