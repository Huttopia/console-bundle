<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle\Command;

use Symfony\Component\Console\{
    Command\ListCommand as SymfonyListCommand,
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

    public function __construct(
        int $symfonyVersionVerbosityLevel,
        int $usageVerbosityLevel,
        int $optionsVerbosityLevel,
        int $availableCommandsVerbosityLevel
    ) {
        parent::__construct();

        $this->symfonyVersionVerbosityLevel = $symfonyVersionVerbosityLevel;
        $this->usageVerbosityLevel = $usageVerbosityLevel;
        $this->optionsVerbosityLevel = $optionsVerbosityLevel;
        $this->availableCommandsVerbosityLevel = $availableCommandsVerbosityLevel;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
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
            ->describe($output, $this->getApplication(), [
                'format' => $input->getOption('format'),
                'raw_text' => $input->getOption('raw'),
                'namespace' => $input->getArgument('namespace'),
            ]);

        return 0;
    }
}
