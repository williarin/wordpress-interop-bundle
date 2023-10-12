<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Williarin\WordpressInteropBundle\DependencyInjection\Compiler\RepositoryCompilerPass;

final class WilliarinWordpressInteropBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RepositoryCompilerPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
