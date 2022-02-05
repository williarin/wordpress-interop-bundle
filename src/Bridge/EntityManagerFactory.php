<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle\Bridge;

use Doctrine\DBAL\Connection;
use Symfony\Component\Serializer\SerializerInterface;
use Williarin\WordpressInterop\EntityManagerInterface;

final class EntityManagerFactory
{
    public static function create(
        Connection $connection,
        SerializerInterface $serializer,
        string $tablePrefix = 'wp_'
    ): EntityManagerInterface {
        return new EntityManager($connection, $serializer, $tablePrefix);
    }
}
