<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Main command of Sync App. Runs the directory synchronization
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class SyncCommand extends ContainerAwareCommand
{
    /**
     * Sets name and description of the command
     */
    protected function configure()
    {
        $this
            ->setName('sync:run')
            ->setDescription('Runs the directory syncronization')
        ;
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input    Input interface
     * @param OutputInterface $output   Output interface
     *
     * @return int 0 if everything went fine
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sync = $this->getContainer()->get('app');

        $masterFilters = $this->getFilters('master');
        $sync->setMasterFilters($masterFilters);

        $slaveFilters = $this->getFilters('slave');
        $sync->setSlaveFilters($slaveFilters);

        $sync->run();
    }

    /**
     * @param string $type  of the filters
     *
     * @return array  of the filter objects
     */
    protected function getFilters($type)
    {
        $filters   = [];
        $container = $this->getContainer();

        $filterConfigs = $container->getParameter($type . '.filters');

        if (!is_array($filterConfigs)) {
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
