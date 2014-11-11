<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $sync = $this->getContainer()->get('app');

        $masterFilters = $this->getFilters('master');
        $sync->setMasterFilters($masterFilters);

        $slaveFilters = $this->getFilters('slave');
        $sync->setSlaveFilters($slaveFilters);

        $sync->run();
    }

    protected function getFilters($type)
    {
        $filters   = [];
        $container = $this->getContainer();

        $filterConfigs = $container->getParameter($type . '.filters');

        if (is_null($filterConfigs)) {
            return [];
        }

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