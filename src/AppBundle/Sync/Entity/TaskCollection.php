<?php
namespace AppBundle\Sync\Entity;

use AppBundle\Sync\Entity\Task;

/**
 * Collection of Tasks
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class TaskCollection extends Collection
{
    /**
     * Add Task to collection
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
