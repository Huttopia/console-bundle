<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle;

use Symfony\Bundle\FrameworkBundle\Console\Application as BundleApplication;
use Symfony\Component\Console\{
    Command\Command,
    Input\InputDefinition,
    Input\InputOption
};
use Symfony\Component\DependencyInjection\ContainerInterface;

class Application extends BundleApplication
{
    /** @var string[] */
    protected $excludedCommands = [];

    /** @var bool */
    protected $allCommands = true;

    public function setAllCommands(bool $allCommands): self
    {
        $this->allCommands = $allCommands;

        return $this;
    }

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

    public function add(Command $command): ?Command
    {
        if (
            $this->allCommands === true
            || count(
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

    protected function getDefaultInputDefinition(): InputDefinition
    {
        $inputDefinition = parent::getDefaultInputDefinition();
        $inputDefinition->addOption(
            new InputOption('--all-commands', null, InputOption::VALUE_NONE, 'Do not exclude commands')
        );

        $options = $inputDefinition->getOptions();
        ksort($options);
        $inputDefinition->setOptions($options);

        return $inputDefinition;
    }
}
