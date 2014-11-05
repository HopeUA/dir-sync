<?php
namespace AppBundle\Sync\Entity\Task;

use AppBundle\Sync\Entity\Task;

class Update extends Task
{
    protected $name = 'update';
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
}