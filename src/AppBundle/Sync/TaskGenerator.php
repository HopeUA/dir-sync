<?php
namespace AppBundle\Sync;

use AppBundle\Exception\TaskException;
use AppBundle\Sync\Entity\FileCollection;
use AppBundle\Sync\Entity\Task\Add;
use AppBundle\Sync\Entity\Task\Delete;
use AppBundle\Sync\Entity\Task\Update;
use AppBundle\Sync\Entity\TaskCollection;
use AppBundle\Sync\Entity\File;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

/**
 * Compare two FileCollections and generate tasks
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class TaskGenerator
{
    /**
     * @var string Path for slave file
     */
    protected $slavePathTpl;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param $path
     */
    public function setSlavePathTpl($path)
    {
        $this->slavePathTpl = $path;
    }

    /**
     * @return string
     *
     * @throws TaskException
     */
    public function getSlavePathTpl()
    {
        if ($this->slavePathTpl == '') {
            throw new TaskException(
                '[TaskGenerator] You must set the Slave Path template',
                TaskException::SLAVE_PATH_NOT_SET
            );
        }

        return $this->slavePathTpl;
    }

    /**
     * Get the slave path form template
     *
     * @param string $uid  File uid
     *
     * @return string  Path to file
     */
    public function getSlavePath($uid)
    {
        $path = $this->getSlavePathTpl();
        $path = str_replace('{uid}', $uid, $path);
        $path = str_replace('{program}', substr($uid, 0, 4), $path);

        return $path;
    }

    /**
     * Compare collections and create tasks
     *
     * @param FileCollection $master
     * @param FileCollection $slave
     *
     * @return TaskCollection
     */
    public function handle(FileCollection $master, FileCollection $slave)
    {
        $masterHash = $this->getHash($master);
        $slaveHash  = $this->getHash($slave);

        $tasks = new TaskCollection();

        $logger = $this->getLogger();

        /**
         * Add and Update
         *
         * @var File $masterFile
         * @var File $slaveFile
         */
        $diff = array_diff_assoc($masterHash, $slaveHash);

        foreach ($diff as $uid => $hash) {
            $masterFile = $master->getByUid($uid);
            $slaveFile  = $slave->getByUid($uid);

            $new = !isset($slaveHash[$uid]);
            if ($new) {
                $task = new Add();
            } else {
                $task = new Update();
            }

            $task->setSourcePath($masterFile->getPath());
            $task->setDestPath($this->getSlavePath($uid));

            $tasks->addTask($task);

            $logger->info(
                sprintf(
                    '[TaskGenerator] Generated "%s" task based on files: S[%s] D[%s]',
                    $task->getName(),
                    $masterFile,
                    $slaveFile
                )
            );
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

            $logger->info(
                sprintf(
                    '[TaskGenerator] Generated "%s" task based on file: D[%s]',
                    $task->getName(),
                    $slaveFile
                )
            );
        }

        return $tasks;
    }

    /**
     * Calculates hashes for FileCollection
     *
     * @param FileCollection $files
     *
     * @return array  of hashes
     */
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

    /**
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        if (is_null($this->logger)) {
            return new NullLogger();
        }

        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
