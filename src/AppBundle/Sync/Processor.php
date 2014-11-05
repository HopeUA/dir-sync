<?php
namespace AppBundle\Sync;

use AppBundle\Exception\TaskException;
use AppBundle\Sync\Entity\Task;
use AppBundle\Sync\Entity\Task\Add;
use AppBundle\Sync\Entity\Task\Delete;
use AppBundle\Sync\Entity\Task\Update;
use AppBundle\Sync\Storage\StorageInterface;

class Processor
{
    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function execute(Task $task)
    {
        $method = $task->getName();
        if (!method_exists($this, $method)) {
            throw new TaskException(sprintf('Can\'t process {%s} task', $method));
        }

        return $this->$method($task);
    }

    protected function add(Add $task)
    {
        return $this->storage->put($task->getSourcePath(), $task->getDestPath());
    }

    protected function delete(Delete $task)
    {
        return $this->storage->delete($task->getDestPath());
    }

    protected function update(Update $task)
    {
        return $this->storage->put($task->getSourcePath(), $task->getDestPath());
    }
}