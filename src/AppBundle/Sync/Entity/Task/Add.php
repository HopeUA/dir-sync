<?php
namespace AppBundle\Sync\Entity\Task;

use AppBundle\Sync\Entity\Task;

/**
 * "Add" task
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class Add extends Task
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'add';
    /**
     * @var string  Source path
     */
    protected $sourcePath;
    /**
     * @var string  Dest path
     */
    protected $destPath;

    /**
     * Get source path
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->sourcePath;
    }

    /**
     * Set source path
     *
     * @param string $sourcePath
     */
    public function setSourcePath($sourcePath)
    {
        $this->sourcePath = $sourcePath;
    }

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
        return sprintf('Copied %s', basename($this->getSourcePath()));
    }
}
