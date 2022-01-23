<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle\Bridge;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Williarin\WordpressInterop\AbstractManagerRegistry;
use Williarin\WordpressInterop\EntityManagerInterface;
use Williarin\WordpressInteropBundle\Exception\InvalidEntityManagerException;

final class ManagerRegistry extends AbstractManagerRegistry
{
    public function __construct(
        private ContainerInterface $container,
        array $managers,
        string $defaultManager
    ) {
        parent::__construct($managers, $defaultManager);
    }

    protected function getService($name): EntityManagerInterface
    {
        $service = $this->container->get($name);

        if (!$service instanceof EntityManagerInterface) {
            throw new InvalidEntityManagerException($service !== null ? $service::class : self::class);
        }

        return $service;
    }
}
