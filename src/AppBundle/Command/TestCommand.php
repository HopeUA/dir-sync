<?php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Sync\Sync;
use AppBundle\Sync\Storage\Local;
use AppBundle\Sync\Entity\Filter\Path;
use Psr\Log\AbstractLogger as Logger;

class TestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sync:test')
            ->setDescription('Test');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filters = $this->getContainer()->getParameter('master.filters');

    }
}