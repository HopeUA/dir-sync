<?php
namespace AppBundle\Sync\Entity\Task;

use AppBundle\Sync\Entity\Task;

/**
 * "Update" task
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class Update extends Add
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'update';

    /**
     * {@inheritdoc}
     */
    public function getMessageSuccess()
    {
        return sprintf('Updated %s', basename($this->getSourcePath()));
    }
}
