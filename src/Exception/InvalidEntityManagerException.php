<?php

declare(strict_types=1);

namespace Williarin\WordpressInteropBundle\Exception;

use Exception;
use Williarin\WordpressInterop\EntityManagerInterface;

final class InvalidEntityManagerException extends Exception
{
    public function __construct(string $className)
    {
        parent::__construct(sprintf(
            'Service "%s" should be an instance of "%s".',
            $className,
            EntityManagerInterface::class
        ));
    }
}
