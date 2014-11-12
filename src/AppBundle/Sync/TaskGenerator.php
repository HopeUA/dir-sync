<?php
namespace AppBundle\Sync;

use AppBundle\Exception\TaskException;
use AppBundle\Sync\Entity\FileCollection;
use AppBundle\Sync\Entity\Task\Add;
use AppBundle\Sync\Entity\Task\Delete;
use AppBundle\Sync\Entity\Task\Update;
use AppBundle\Sync\Entity\TaskCollection;
use AppBundle\Sync\Entity\File;

class TaskGenerator
{
    /**
     * @var string Path for slave file
     */
    protected $slavePathTpl;

    public function setSlavePathTpl($path)
    {
        $this->slavePathTpl = $path;
    }

    public function getSlavePathTpl()
    {
        if ($this->slavePathTpl == '') {
            throw new TaskException('You must set the Slave Path template', TaskException::SLAVE_PATH_NOT_SET);
        }

        return $this->slavePathTpl;
    }

    public function getSlavePath($uid)
    {
        $path = $this->getSlavePathTpl();
        $path = str_replace('{uid}', $uid, $path);
        $path = str_replace('{program}', substr($uid, 0, 4), $path);

        return $path;
    }

    public function handle(FileCollection $master, FileCollection $slave)
    {
        $masterHash = $this->getHash($master);
        $slaveHash  = $this->getHash($slave);

        $tasks = new TaskCollection();

        /**
         * Add and Update
         *
         * @var File $masterFile
         * @var File $slaveFile
         */
        $diff = array_diff_assoc($masterHash, $slaveHash);

        foreach ($diff as $uid => $hash) {
            $masterFile = $master->getByUid($uid);

            $new = !isset($slaveHash[$uid]);
            if ($new) {
                $task = new Add();
            } else {
                $task = new Update();
            }

            $task->setSourcePath($masterFile->getPath());
            $task->setDestPath($this->getSlavePath($uid));

            $tasks->addTask($task);
        }

        // Delete
        $diff = array_diff_assoc($slaveHash, $masterHash);

        foreach ($diff as $uid => $hash) {
            // Skip changed files
            if (isset($masterHash[$uid])) {
                continue;
            }

            // Delete task
            $slaveFile = $slave->getByUid($uid);

            $task = new Delete();
            $task->setDestPath($slaveFile->getPath());

            $tasks->addTask($task);
        }

        return $tasks;
    }

    private function getHash(FileCollection $files)
    {
        $hash = [];

        /**
         * @var File $file
         */
        foreach ($files as $file) {
            $hash[$file->getUid()] = md5($file->getUid() . $file->getSize() . $file->getModified()->format('dmyHis'));
        }

        return $hash;
    }
}
