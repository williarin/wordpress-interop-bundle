<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class WilliarinWordpressInteropBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
