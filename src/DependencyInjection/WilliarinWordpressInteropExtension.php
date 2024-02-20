<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Williarin\WordpressInterop\Bridge\Repository\RepositoryInterface;
use Williarin\WordpressInterop\Persistence\DuplicationServiceInterface;

final class WilliarinWordpressInteropExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('framework', [
            'serializer' => [
                'name_converter' => 'serializer.name_converter.camel_case_to_snake_case',
            ],
        ]);
    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $yamlFileLoader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $yamlFileLoader->load('services.yaml');

        $container->registerForAutoconfiguration(RepositoryInterface::class)
            ->addTag('wordpress_interop.repository')
        ;

        if (empty($mergedConfig['entity_managers'])) {
            return;
        }

        $managers = [];

        foreach (array_keys($mergedConfig['entity_managers'] ?? []) as $name) {
            $managers[$name] = sprintf('wordpress_interop.entity_manager.%s', $name);
        }

        $container->setParameter('wordpress_interop.entity_managers', $managers);

        $defaultEntityManager = $mergedConfig['default_entity_manager'] ?? array_key_first($managers);

        $defaultEntityManagerDefinitionId = sprintf('wordpress_interop.entity_manager.%s', $defaultEntityManager);

        $container->setParameter('wordpress_interop.default_entity_manager', $defaultEntityManager);
        $container->setAlias('wordpress_interop.entity_manager', $defaultEntityManagerDefinitionId);
        $container->getAlias('wordpress_interop.entity_manager')
            ->setPublic(true)
        ;

        foreach ($mergedConfig['entity_managers'] ?? [] as $name => $options) {
            $entityManagerId = sprintf('wordpress_interop.entity_manager.%s', $name);

            $container
                ->setDefinition($entityManagerId, new ChildDefinition('wordpress_interop.entity_manager.abstract'))
                ->setPublic(true)
                ->setArguments([
                    new Reference(sprintf('doctrine.dbal.%s_connection', $options['connection'])),
                    new Reference('serializer'),
                    $options['tables_prefix'],
                    new Reference(DuplicationServiceInterface::class),
                ])
                ->addMethodCall('setContainer', [new Reference('service_container')])
            ;
        }
    }
}
