<?php

namespace Huttopia\ConsoleBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UpdateAllDatabaseSchemaCommand extends UpdateSchemaDoctrineCommand
{
    /** Only to type $this->getApplication() with right object */
    public function getApplication(): Application
    {
        return parent::getApplication();
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('doctrine:schema:update')
            ->setDescription('Update all databases configured in console.databases');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $databases = $this->getContainer()->getParameter('console.databases');
        if (count($databases) === 0) {
            $output->writeln('<comment>No configured databases in <info>console.databases</info>.</comment>');
        } else {
            foreach ($this->getContainer()->getParameter('console.databases') as $name) {
                $this->executeUpdateDatabaseSchemaCommand(
                    $name,
                    $input->getOption('em'),
                    $input->getOption('dump-sql'),
                    $input->getOption('force'),
                    $input->getOption('complete'),
                    $output
                );
            }
        }

        return 0;
    }

    protected function executeUpdateDatabaseSchemaCommand(
        string $name,
        ?string $em,
        bool $dumpSql,
        bool $force,
        bool $complete,
        OutputInterface $output
    ): self {
        $output->writeln('<info>' . $name . '</info>');
        $command =
            'php '
            . $this->getApplication()->getKernel()->getRootDir() . '/../bin/console '
            . 'doctrine:schema:db-update '
            . $name;
        if ($em !== null) {
            $command .= ' --em=' . $em;
        }
        if ($dumpSql) {
            $command .= ' --dump-sql';
        }
        if ($force) {
            $command .= ' --force';
        }
        if ($complete) {
            $command .= ' --complete';
        }

        $process = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['file', $this->getApplication()->getKernel()->getCacheDir() . 'update-schema-cache.' . $name, 'a']
            ],
            $pipes
        );

        $output->writeln(stream_get_contents($pipes[1]));
        fclose($pipes[1]);
        proc_close($process);

        return $this;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->getApplication()->getKernel()->getContainer();
    }
}
