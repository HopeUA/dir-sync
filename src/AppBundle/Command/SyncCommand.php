<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Sync\Sync;
use AppBundle\Sync\Storage\Local;
use AppBundle\Sync\Entity\Filter\Path;
use Psr\Log\AbstractLogger as Logger;

class SyncCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sync:run')
            ->setDescription('Runs the directory syncronization')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var Logger $logger
         */
        $logger = $this->getContainer()->get('app_logger');

        $sync = new Sync();
        $sync->setLogger($logger);

        // Set up Master
        $pathFilter = new Path('~[A-Z]{4}/stream/.*\.mov~');

        $masterStorage = new Local();
        $masterPath    = 'root/source';
        $masterFilters = [$pathFilter];

        $sync->setMasterStorage($masterStorage);
        $sync->setMasterPath($masterPath);
        $sync->setMasterFilters($masterFilters);

        // Set up Slave
        $slaveStorage = new Local();
        $slavePath    = 'root/dest';
        $slavePathTpl = $slavePath . '/{program}/{uid}';

        $sync->setSlaveStorage($slaveStorage);
        $sync->setSlavePath($slavePath);
        $sync->setSlavePathTpl($slavePathTpl);

        // Run
        $sync->run();
    }
}