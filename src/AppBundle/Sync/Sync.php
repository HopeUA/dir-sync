<?php
namespace AppBundle\Sync;

use AppBundle\Exception\StorageException;
use AppBundle\Exception\TaskException;
use AppBundle\Sync\Storage\AbstractStorage as Storage;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

class Sync
{
    /**
     * @var Storage  Main Storage object
     */
    protected $masterStorage;
    /**
     * @var string  Root path for sync
     */
    protected $masterPath;
    /**
     * @var array  Filters for file list
     */
    protected $masterFilters = [];

    /**
     * @var Storage  Slave Storage object
     */
    protected $slaveStorage;
    /**
     * @var string  Root path for sync
     */
    protected $slavePath;
    /**
     * @var array  Filters for file list
     */
    protected $slaveFilters = [];
    /**
     * @var string  Template for new files destination
     */
    protected $slavePathTpl;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Runs the sync tasks
     */
    public function run()
    {
        $logger = $this->getLogger();

        // Log
        $logger->info('START Synchronization');

        $masterFiles = $this->getFiles($this->getMasterStorage(), $this->getMasterPath(), $this->getMasterFilters());
        $logger->info(sprintf('Master files count: %d', count($masterFiles)));

        $slaveFiles  = $this->getFiles($this->getSlaveStorage(), $this->getSlavePath(), $this->getSlaveFilters());
        $logger->info(sprintf('Slave files count: %d', count($slaveFiles)));

        $generator = new TaskGenerator($masterFiles, $slaveFiles);
        $generator->setSlavePathTpl($this->getSlavePathTpl());

        $tasks = $generator->handle($masterFiles, $slaveFiles);
        $logger->info(sprintf('Generated %d tasks', count($tasks)));

        $processor = new Processor($this->getSlaveStorage());
        foreach ($tasks as $task) {
            try {
                $result = $processor->execute($task);
                $logger->info($result);
            } catch (TaskException $e) {
                $logger->error($e->getMessage());
            } catch (StorageException $e) {
                $logger->error($e->getMessage());
            }
        }

        // Log
        $logger->info('END Synchronization');
    }

    /**
     * Gets filtered file list for selected storage
     *
     * @param Storage $storage
     * @param         $path
     * @param array   $filters
     *
     * @return Entity\FileCollection
     */
    protected function getFiles(Storage $storage, $path, array $filters)
    {
        $fc = $storage->listContents($path);
        $fc->filter($filters);

        return $fc;
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

    /**
     * @return array
     */
    protected function getMasterFilters()
    {
        return $this->masterFilters;
    }

    /**
     * @param array $masterFilters
     */
    public function setMasterFilters(array $masterFilters)
    {
        $this->masterFilters = $masterFilters;
    }

    /**
     * @return string
     */
    protected function getMasterPath()
    {
        return $this->masterPath;
    }

    /**
     * @param string $masterPath
     */
    public function setMasterPath($masterPath)
    {
        $this->masterPath = $masterPath;
    }

    /**
     * @return Storage
     */
    protected function getMasterStorage()
    {
        return $this->masterStorage;
    }

    /**
     * @param Storage $masterStorage
     */
    public function setMasterStorage(Storage $masterStorage)
    {
        $this->masterStorage = $masterStorage;
    }

    /**
     * @return array
     */
    protected function getSlaveFilters()
    {
        return $this->slaveFilters;
    }

    /**
     * @param array $slaveFilters
     */
    public function setSlaveFilters(array $slaveFilters)
    {
        $this->slaveFilters = $slaveFilters;
    }

    /**
     * @return string
     */
    protected function getSlavePath()
    {
        return $this->slavePath;
    }

    /**
     * @param string $slavePath
     */
    public function setSlavePath($slavePath)
    {
        $this->slavePath = $slavePath;
    }

    /**
     * @return Storage
     */
    protected function getSlaveStorage()
    {
        return $this->slaveStorage;
    }

    /**
     * @param Storage $slaveStorage
     */
    public function setSlaveStorage(Storage $slaveStorage)
    {
        $this->slaveStorage = $slaveStorage;
    }

    /**
     * @return string
     */
    protected function getSlavePathTpl()
    {
        return $this->slavePathTpl;
    }

    /**
     * @param string $slavePathTpl
     */
    public function setSlavePathTpl($slavePathTpl)
    {
        $this->slavePathTpl = $slavePathTpl;
    }
}