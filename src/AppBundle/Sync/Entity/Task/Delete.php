<?php
namespace AppBundle\Sync\Entity\Task;

use AppBundle\Sync\Entity\Task;

class Delete extends Task
{
    protected $name = 'delete';
    protected $destPath;

    /**
     * @return mixed
     */
    public function getDestPath()
    {
        return $this->destPath;
    }

    /**
     * @param mixed $destPath
     */
    public function setDestPath($destPath)
    {
        $this->destPath = $destPath;
    }

    public function getMessageSuccess()
    {
        return sprintf('Deleted %s', basename($this->getDestPath()));
    }
}
