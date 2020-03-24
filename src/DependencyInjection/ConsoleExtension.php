<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\{
    ContainerBuilder,
    Loader\YamlFileLoader
};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class ConsoleExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $this
            ->readConfiguration($configs, $container)
            ->loadServices($container);
    }

    protected function readConfiguration(array $configs, ContainerBuilder $container): self
    {
        $configuration = $this->processConfiguration(new Configuration(), $configs);

        $container->setParameter('console.excluded', $configuration['excluded']);
        $container->setParameter('console.databases', $configuration['databases']);

        $this->defineOutputParameters($configuration['list']['output'] ?? [], $container);

        return $this
            ->defineVerbosityLevelParameter('symfonyVersionVerbosityLevel', $container, $configuration)
            ->defineVerbosityLevelParameter('usageVerbosityLevel', $container, $configuration)
            ->defineVerbosityLevelParameter('optionsVerbosityLevel', $container, $configuration)
            ->defineVerbosityLevelParameter('availableCommandsVerbosityLevel', $container, $configuration);
    }

    protected function loadServices(ContainerBuilder $container): self
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        return $this;
    }

    protected function defineVerbosityLevelParameter(
        string $name,
        ContainerBuilder $container,
        array $configuration
    ): self {
        $container->setParameter(
            "console.list.$name",
            $this->convertVerbosityLevel($configuration['list'][$name] ?? 0)
        );

        return $this;
    }

    protected function convertVerbosityLevel(int $level): int
    {
        switch ($level) {
            case 0: $return = OutputInterface::VERBOSITY_NORMAL; break;
            case 1: $return = OutputInterface::VERBOSITY_VERBOSE; break;
            case 2: $return = OutputInterface::VERBOSITY_VERY_VERBOSE; break;
            default: $return = OutputInterface::VERBOSITY_DEBUG; break;
        }

        return $return;
    }

    protected function defineOutputParameters(array $configuration, ContainerBuilder $container): self
    {
        $container->setParameter('console.list.output.styles', $configuration['styles'] ?? []);
        $container->setParameter('console.list.output.commands', $configuration['commands'] ?? []);
        $container->setParameter('console.list.output.highlights', $configuration['highlights'] ?? []);

        return $this;
    }
}
