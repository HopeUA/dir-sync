<?php
namespace Tests\AppBundle\Sync;

use AppBundle\Sync\Entity\Task;
use AppBundle\Sync\Processor;
use AppBundle\Sync\Entity\Task\Add;
use AppBundle\Sync\Entity\Task\Delete;
use AppBundle\Sync\Entity\Task\Update;
use AppBundle\Sync\Storage\StorageInterface;

/**
 * Task Processor tests
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $storage = $this->getMockForAbstractClass(StorageInterface::class);
        $storage->expects($this->once())
                ->method('put');

        $processor = new Processor($storage);
        $task = new Add();

        $processor->execute($task);
    }

    public function testUpdate()
    {
        $storage = $this->getMockForAbstractClass(StorageInterface::class);
        $storage->expects($this->once())
                ->method('put');

        $processor = new Processor($storage);
        $task = new Update();

        $processor->execute($task);
    }

    public function testDelete()
    {
        $storage = $this->getMockForAbstractClass(StorageInterface::class);
        $storage->expects($this->once())
                ->method('delete');

        $processor = new Processor($storage);
        $task = new Delete();

        $processor->execute($task);
    }

    /**
     * @expectedException \AppBundle\Exception\TaskException
     * @expectedExceptionCode \AppBundle\Exception\TaskException::INVALID_TASK
     */
    public function testInvalidTask()
    {
        $storage = $this->getMockForAbstractClass(StorageInterface::class);
        $processor = new Processor($storage);
        $task = $this->getMockBuilder(Task::class)
            ->getMock();

        $processor->execute($task);
    }
}
