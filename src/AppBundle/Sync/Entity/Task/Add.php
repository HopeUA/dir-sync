<?php
namespace AppBundle\Sync\Entity\Task;

use AppBundle\Sync\Entity\Task;

class Add extends Task
{
    protected $name = 'add';
    protected $sourcePath;
    protected $destPath;

    /**
     * @return mixed
     */
    public function getSourcePath()
    {
        return $this->sourcePath;
    }

    /**
     * @param mixed $sourcePath
     */
    public function setSourcePath($sourcePath)
    {
        $this->sourcePath = $sourcePath;
    }

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
        return sprintf('Copied %s', basename($this->getSourcePath()));
    }
}