<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class WilliarinWordpressInteropExtension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $containerBuilder): void
    {
        $yamlFileLoader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../config'));
        $yamlFileLoader->load('services.yaml');

        $managers = [];

        foreach (array_keys($mergedConfig['entity_managers'] ?? []) as $name) {
            $managers[$name] = sprintf('wordpress_interop.entity_manager.%s', $name);
        }

        $containerBuilder->setParameter('wordpress_interop.entity_managers', $managers);

        $defaultEntityManager = $mergedConfig['default_entity_manager'] ?? array_key_first($managers);

        $defaultEntityManagerDefinitionId = sprintf('wordpress_interop.entity_manager.%s', $defaultEntityManager);

        $containerBuilder->setParameter('wordpress_interop.default_entity_manager', $defaultEntityManager);
        $containerBuilder->setAlias('wordpress_interop.entity_manager', $defaultEntityManagerDefinitionId);
        $containerBuilder->getAlias('wordpress_interop.entity_manager')
            ->setPublic(true)
        ;

        foreach ($mergedConfig['entity_managers'] ?? [] as $name => $options) {
            $entityManagerId = sprintf('wordpress_interop.entity_manager.%s', $name);

            $containerBuilder
                ->setDefinition($entityManagerId, new ChildDefinition('wordpress_interop.entity_manager.abstract'))
                ->setPublic(true)
                ->setArguments([
                    new Reference(sprintf('doctrine.dbal.%s_connection', $options['connection'])),
                    new Reference('serializer'),
                    $options['tables_prefix'],
                ])
            ;
        }
    }
}
