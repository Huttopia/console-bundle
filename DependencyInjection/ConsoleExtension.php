<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ConsoleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('console.excluded', $configuration['excluded']);
    }
}
