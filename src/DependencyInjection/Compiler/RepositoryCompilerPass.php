<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RepositoryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $repositories = $container->findTaggedServiceIds('wordpress_interop.repository');

        foreach ($repositories as $id => $tags) {
            $definition = $container->findDefinition($id);
            $definition->setPublic(true);
        }
    }
}
