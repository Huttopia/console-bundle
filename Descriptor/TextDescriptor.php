<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle\Descriptor;

use Symfony\Component\Console\{
    Application,
    Command\Command,
    Descriptor\ApplicationDescription,
    Descriptor\TextDescriptor as SymfonyTextDescriptor,
    Helper\Helper,
    Input\InputDefinition
};

class TextDescriptor extends SymfonyTextDescriptor
{
    /** @var int */
    protected $verbosity;

    /** @var int */
    protected $symfonyVersionVerbosityLevel;

    /** @var int */
    protected $usageVerbosityLevel;

    /** @var int */
    protected $optionsVerbosityLevel;

    /** @var int */
    protected $availableCommandsVerbosityLevel;

    public function __construct(
        int $verbosity,
        int $symfonyVersionVerbosityLevel,
        int $usageVerbosityLevel,
        int $optionsVerbosityLevel,
        int $availableCommandsVerbosityLevel
    ) {
        $this->verbosity = $verbosity;
        $this->symfonyVersionVerbosityLevel = $symfonyVersionVerbosityLevel;
        $this->usageVerbosityLevel = $usageVerbosityLevel;
        $this->optionsVerbosityLevel = $optionsVerbosityLevel;
        $this->availableCommandsVerbosityLevel = $availableCommandsVerbosityLevel;
    }

    protected function describeApplication(Application $application, array $options = [])
    {
        if (
            $this->symfonyVersionVerbosityLevel === 0
            && $this->usageVerbosityLevel === 0
            && $this->optionsVerbosityLevel === 0
            && $this->availableCommandsVerbosityLevel === 0
        ) {
            return parent::describeApplication($application, $options);
        }

        if (isset($options['raw_text']) && $options['raw_text']) {
            return parent::describeApplication($application, $options);
        } else {
            if ($this->symfonyVersionVerbosityLevel <= $this->verbosity) {
                $this->writeSymfonyVersion($application, $options);
            }

            if ($this->usageVerbosityLevel <= $this->verbosity) {
                $this->writeUsage($options);
            }

            if ($this->optionsVerbosityLevel <= $this->verbosity) {
                $this->writeOptions($application, $options);
            }

            if ($this->availableCommandsVerbosityLevel <= $this->verbosity) {
                $this->writeCommandsList($application, $options);
            }
        }
    }

    protected function writeSymfonyVersion(Application $application, array $options): self
    {
        $help = $application->getHelp();
        if (is_string($help) && $help !== '') {
            $this
                ->writeText($help, $options)
                ->writeLn()
                ->writeLn();
        }

        return $this;
    }

    protected function writeUsage(array $options): self
    {
        return $this
            ->writeText("<comment>Usage:</comment>\n", $options)
            ->writeText("  command [options] [arguments]\n\n", $options);
    }

    protected function writeOptions(Application $application, array $options): self
    {
        $this->describeInputDefinition(new InputDefinition($application->getDefinition()->getOptions()), $options);

        return $this
            ->writeLn()
            ->writeLn();
    }

    /** Code mostly copied from Symfony\Component\Console\Descriptor\TextDescriptor */
    protected function writeCommandsList(Application $application, array $options): self
    {
        $describedNamespace = isset($options['namespace']) ? $options['namespace'] : null;
        $description = new ApplicationDescription($application, $describedNamespace);

        $commands = $description->getCommands();
        $namespaces = $description->getNamespaces();
        if ($describedNamespace && $namespaces) {
            // make sure all alias commands are included when describing a specific namespace
            $describedNamespaceInfo = reset($namespaces);
            foreach ($describedNamespaceInfo['commands'] as $name) {
                $commands[$name] = $description->getCommand($name);
            }
        }

        // calculate max. width based on available commands per namespace
        $width = $this->getColumnWidth(
            array_merge(
                ...array_values(
                    array_map(
                        function ($namespace) use ($commands) {
                            return array_intersect($namespace['commands'], array_keys($commands));
                        },
                        $namespaces
                    )
                )
            )
        );

        if ($describedNamespace) {
            $this->writeText(
                sprintf('<comment>Available commands for the "%s" namespace:</comment>', $describedNamespace),
                $options
            );
        } else {
            $this->writeText('<comment>Available commands:</comment>', $options);
        }

        foreach ($namespaces as $namespace) {
            $namespace['commands'] = array_filter(
                $namespace['commands'],
                function ($name) use ($commands) {
                    return isset($commands[$name]);
                }
            );

            if (!$namespace['commands']) {
                continue;
            }

            if (!$describedNamespace && ApplicationDescription::GLOBAL_NAMESPACE !== $namespace['id']) {
                $this
                    ->writeLn()
                    ->writeText(' <comment>' . $namespace['id'] . '</comment>', $options);
            }

            foreach ($namespace['commands'] as $name) {
                $this->writeLn();
                $spacingWidth = $width - Helper::strlen($name);
                $command = $commands[$name];
                $commandAliases = $name === $command->getName() ? $this->getCommandAliasesText($command) : '';
                $this->writeText(
                    sprintf(
                        '  <info>%s</info>%s%s',
                        $name,
                        str_repeat(' ', $spacingWidth),
                        $commandAliases . $command->getDescription()
                    ),
                    $options
                );
            }
        }

        return $this->writeLn();
    }

    protected function writeText(string $content, array $options = []): self
    {
        $this->write(
            isset($options['raw_text']) && $options['raw_text'] ? strip_tags($content) : $content,
            isset($options['raw_output']) ? !$options['raw_output'] : true
        );

        return $this;
    }

    protected function writeLn(): self
    {
        return $this->writeText("\n");
    }

    protected function getColumnWidth(array $commands): int
    {
        $widths = [];

        foreach ($commands as $command) {
            if ($command instanceof Command) {
                $widths[] = Helper::strlen($command->getName());
                foreach ($command->getAliases() as $alias) {
                    $widths[] = Helper::strlen($alias);
                }
            } else {
                $widths[] = Helper::strlen($command);
            }
        }

        return $widths ? max($widths) + 2 : 0;
    }

    protected function getCommandAliasesText(Command $command): string
    {
        $return = '';
        $aliases = $command->getAliases();

        if (count($aliases) > 0) {
            $return = '[' . implode('|', $aliases) . '] ';
        }

        return $return;
    }
}
