<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle\Command;

use Symfony\Component\Console\{
    Command\ListCommand as SymfonyListCommand,
    Formatter\OutputFormatterStyle,
    Helper\DescriptorHelper,
    Input\InputInterface,
    Output\OutputInterface
};
use Huttopia\ConsoleBundle\Descriptor\TextDescriptor;

class ListCommand extends SymfonyListCommand
{
    /** @var int */
    protected $symfonyVersionVerbosityLevel = 0;

    /** @var int */
    protected $usageVerbosityLevel = 0;

    /** @var int */
    protected $optionsVerbosityLevel = 0;

    /** @var int */
    protected $availableCommandsVerbosityLevel = 0;

    /** @var array */
    protected $outputStyles;

    /** @var array|string[] */
    protected $outputCommands = [];

    public function __construct(
        int $symfonyVersionVerbosityLevel,
        int $usageVerbosityLevel,
        int $optionsVerbosityLevel,
        int $availableCommandsVerbosityLevel,
        array $outputStyles,
        array $outputCommands,
        array $highlights
    ) {
        parent::__construct();

        $this->symfonyVersionVerbosityLevel = $symfonyVersionVerbosityLevel;
        $this->usageVerbosityLevel = $usageVerbosityLevel;
        $this->optionsVerbosityLevel = $optionsVerbosityLevel;
        $this->availableCommandsVerbosityLevel = $availableCommandsVerbosityLevel;
        $this->outputStyles = $outputStyles;
        foreach ($highlights as $highlight) {
            # Replace - by _ to have same behavior as symfony/yaml with keys for console.list.output.commands config
            $this->outputCommands[str_replace('-', '_', $highlight)]
                = '<highlight>%s</highlight>%s<highlight>%s</highlight>';
        }
        $this->outputCommands = array_merge($this->outputCommands, $outputCommands);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this
            ->addOutputStyles($output, $this->outputStyles)
            ->describe($input, $output);

        return 0;
    }

    protected function addOutputStyles(OutputInterface $output, array $outputStyles): self
    {
        $output->getFormatter()->setStyle(
            'highlight',
            new OutputFormatterStyle('cyan')
        );

        foreach ($outputStyles as $outputStyleName => $outputStyle) {
            $output->getFormatter()->setStyle(
                $outputStyleName,
                new OutputFormatterStyle(
                    $outputStyle['foreground'],
                    $outputStyle['background'],
                    $outputStyle['options']
                )
            );
        }

        return $this;
    }

    protected function describe(InputInterface $input, OutputInterface $output): self
    {
        (new DescriptorHelper())
            ->register(
                'txt',
                new TextDescriptor(
                    $output->getVerbosity(),
                    $this->symfonyVersionVerbosityLevel,
                    $this->usageVerbosityLevel,
                    $this->optionsVerbosityLevel,
                    $this->availableCommandsVerbosityLevel
                )
            )
            ->describe(
                $output,
                $this->getApplication(),
                [
                    'format' => $input->getOption('format'),
                    'raw_text' => $input->getOption('raw'),
                    'namespace' => $input->getArgument('namespace'),
                    'outputCommands' => $this->outputCommands
                ]
            );

        return $this;
    }
}
