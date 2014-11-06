<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Sync\Sync;
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
        $container = $this->getContainer();

        /**
         * @var Logger $logger
         */
        $logger = $container->get('app_logger');

        $sync = new Sync();
        $sync->setLogger($logger);

        // Set up Master
        $masterStorage = $container->get('master.storage');
        $masterPath    = $container->getParameter('master.path');
        $masterFilters = $this->getFilters('master');;

        $sync->setMasterStorage($masterStorage);
        $sync->setMasterPath($masterPath);
        $sync->setMasterFilters($masterFilters);

        // Set up Slave
        $slaveStorage = $container->get('slave.storage');
        $slavePath    = $container->getParameter('slave.path');
        $slavePathTpl = $slavePath . $container->getParameter('slave.path_tpl');

        $sync->setSlaveStorage($slaveStorage);
        $sync->setSlavePath($slavePath);
        $sync->setSlavePathTpl($slavePathTpl);

        // Run
        $sync->run();
    }

    protected function getFilters($type)
    {
        $filters   = [];
        $container = $this->getContainer();

        $filterConfigs = $container->getParameter($type . '.filters');

        foreach ($filterConfigs as $name => $options) {
            $filter = $container->get('filter.' . $name);

            foreach ($options as $option => $value) {
                $setter = 'set' . ucfirst($option);
                $filter->$setter($value);
            }

            $filters[] = $filter;
        }

        return $filters;
    }
}