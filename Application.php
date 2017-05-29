<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle;

use Symfony\Bundle\FrameworkBundle\Console\Application as BundleApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Application extends BundleApplication
{
    protected $excludedCommands = [];

    public function addExcludedCommand(string $name): self
    {
        $this->excludedCommands[] = $name;

        return $this;
    }

    public function getExcludedCommands(): array
    {
        $return = $this->excludedCommands;

        // help and list commands are added before container instantiation
        if ($this->getKernel()->getContainer() instanceof ContainerInterface) {
            $return = array_merge($return, $this->getKernel()->getContainer()->getParameter('console.excluded'));
        }

        return $return;
    }

    public function add(Command $command)
    {
        if (
            count(
                array_intersect(
                    array_merge([$command->getName()], $command->getAliases()),
                    static::getExcludedCommands()
                )
            ) === 0
        ) {
            $return = parent::add($command);
        } else {
            $return = null;
        }

        return $return;
    }
}
