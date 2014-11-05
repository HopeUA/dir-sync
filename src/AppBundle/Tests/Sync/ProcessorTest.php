<?php
namespace AppBundle\Tests\Sync;

use AppBundle\Sync\Processor;
use AppBundle\Sync\Entity\Task\Add;
use AppBundle\Sync\Entity\Task\Delete;
use AppBundle\Sync\Entity\Task\Update;

class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $storage = $this->getMock('\\AppBundle\\Sync\\Storage\\AbstractStorage');
        $storage->expects($this->once())
                ->method('put');

        $processor = new Processor($storage);
        $task = new Add();

        $processor->execute($task);
    }

    public function testUpdate()
    {
        $storage = $this->getMock('\\AppBundle\\Sync\\Storage\\AbstractStorage');
        $storage->expects($this->once())
                ->method('put');

        $processor = new Processor($storage);
        $task = new Update();

        $processor->execute($task);
    }

    public function testDelete()
    {
        $storage = $this->getMock('\\AppBundle\\Sync\\Storage\\AbstractStorage');
        $storage->expects($this->once())
                ->method('delete');

        $processor = new Processor($storage);
        $task = new Delete();

        $processor->execute($task);
    }
}