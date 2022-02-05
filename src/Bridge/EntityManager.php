<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle\Bridge;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Williarin\WordpressInterop\AbstractEntityManager;
use Williarin\WordpressInterop\Bridge\Repository\AbstractEntityRepository;
use Williarin\WordpressInterop\Bridge\Repository\RepositoryInterface;

class EntityManager extends AbstractEntityManager
{
    private ContainerInterface $container;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }

    protected function getRepositoryServiceForClass(?string $entityClassName): RepositoryInterface
    {
        $repositoryServiceName = $this->getRepositoryNameForClass($entityClassName);

        $repository = $repositoryServiceName
            ? $this->container->get($repositoryServiceName)
            : new class($entityClassName) extends AbstractEntityRepository {
            };

        $repository->setSerializer($this->serializer);

        return $repository;
    }
}
