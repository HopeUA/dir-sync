<?php
namespace AppBundle\Sync\Entity;

/**
 * Abstract class for task implementation
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
abstract class Task extends Entity
{
    /**
     * @var string  Name of the task
     */
    protected $name;

    /**
     * Get task name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the result message after task execution
     *
     * @return string  Success message
     */
    abstract public function getMessageSuccess();
}
