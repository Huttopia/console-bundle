<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\{
    Input\InputArgument,
    Input\InputInterface,
    Output\OutputInterface
};

class UpdateDatabaseSchemaCommand extends UpdateSchemaDoctrineCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('doctrine:schema:db-update')
            ->addArgument('dbname', InputArgument::REQUIRED, 'Database name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->defineDbName($input->getArgument('dbname'));

        return parent::execute($input, $output);
    }

    /** Not really proud of this code, but until Doctrine will define everything as private ... */
    protected function defineDbName(string $name)
    {
        /** @var Application $application */
        $application = $this->getApplication();
        $connection = $application->getKernel()->getContainer()->get('doctrine')->getManager()->getConnection();

        $reflectionProperty = new \ReflectionProperty(get_class($connection), '_params');
        $reflectionProperty->setAccessible(true);
        $params = $reflectionProperty->getValue($connection);
        $params['dbname'] = $name;
        $reflectionProperty->setValue($connection, $params);
        $reflectionProperty->setAccessible(false);
    }
}
