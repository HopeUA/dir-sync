<?php
namespace AppBundle\Sync\Entity\Task;

use AppBundle\Sync\Entity\Task;

/**
 * "Delete" task
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class Delete extends Task
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'delete';
    /**
     * @var string  Dest path
     */
    protected $destPath;

    /**
     * Get dest path
     *
     * @return string
     */
    public function getDestPath()
    {
        return $this->destPath;
    }

    /**
     * Set dest path
     *
     * @param string $destPath
     */
    public function setDestPath($destPath)
    {
        $this->destPath = $destPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageSuccess()
    {
        return sprintf('Deleted %s', basename($this->getDestPath()));
    }
}
