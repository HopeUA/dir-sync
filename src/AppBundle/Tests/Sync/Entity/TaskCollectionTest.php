<?php
namespace AppBundle\Tests\Sync\Entity;

use AppBundle\Sync\Entity\Task;
use AppBundle\Sync\Entity\TaskCollection;

/**
 * Task Collection tests
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class TaskCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        /**
         * @var Task $task
         */
        $task = $this->getMockBuilder(Task::class)
            ->getMock();

        // Create collection
        $collection = new TaskCollection();
        $this->assertCount(0, $collection);

        // Add task
        $collection->addTask($task);
        $this->assertCount(1, $collection);

        // Check task
        $this->assertEquals($task, $collection->get(0));

        // Add task one more time
        $collection->addTask($task);
        $this->assertCount(2, $collection);
    }
}
