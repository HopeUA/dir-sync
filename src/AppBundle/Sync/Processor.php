<?php
namespace AppBundle\Sync;

use AppBundle\Exception\TaskException;
use AppBundle\Sync\Entity\Task;
use AppBundle\Sync\Entity\Task\Add;
use AppBundle\Sync\Entity\Task\Delete;
use AppBundle\Sync\Entity\Task\Update;
use AppBundle\Sync\Storage\StorageInterface;

/**
 * Task processor
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class Processor
{
    /**
     * @var StorageInterface  Storage for task
     */
    protected $storage;

    /**
     * Init the storage
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Executes task
     *
     * @param Task $task
     *
     * @return string  Result message
     *
     * @throws TaskException
     */
    public function execute(Task $task)
    {
        $method = $task->getName();
        if (!method_exists($this, $method)) {
            throw new TaskException(sprintf('Can\'t process {%s} task', $method), TaskException::INVALID_TASK);
        }

        $this->$method($task);

        return $task->getMessageSuccess();
    }

    /**
     * Execute Add task
     *
     * @param Add $task
     */
    protected function add(Add $task)
    {
        $this->storage->put($task->getSourcePath(), $task->getDestPath());
    }

    /**
     * Execute Delete task
     *
     * @param Delete $task
     */
    protected function delete(Delete $task)
    {
        $this->storage->delete($task->getDestPath());
    }

    /**
     * Execute Update task
     *
     * @param Update $task
     */
    protected function update(Update $task)
    {
        $this->storage->put($task->getSourcePath(), $task->getDestPath());
    }
}
