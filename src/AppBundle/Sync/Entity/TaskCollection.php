<?php
namespace AppBundle\Sync\Entity;

use AppBundle\Sync\Entity\Task;

class TaskCollection extends Collection
{
    /**
     * Adds Task to collection
     *
     * @param Task $task
     */
    public function addTask(Task $task)
    {
        $this->add($task);
    }

    /**
     * Get Task from collection by key
     *
     * @param string $key  Task key
     *
     * @return Task
     */
    public function get($key)
    {
        return parent::get($key);
    }
}