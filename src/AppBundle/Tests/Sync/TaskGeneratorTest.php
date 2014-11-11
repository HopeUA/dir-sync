<?php
namespace AppBundle\Tests\Sync;

use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\FileCollection;
use AppBundle\Sync\TaskGenerator;
use Nelmio\Alice\Loader\Yaml;

class TaskGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccessHandle()
    {
        // Sample files
        $loader = new Yaml();
        $files = $loader->load(__DIR__ . '/../Fixtures/files.yml');

        // Collecting master
        $masterCollection = new FileCollection();
        $masterCollection->addFile($files['file1']);
        $masterCollection->addFile($files['file2']);
        $masterCollection->addFile($files['file3']);

        // Collecting slave
        $slaveCollection  = new FileCollection();
        $slaveCollection->addFile($files['file1']);
        $slaveCollection->addFile($files['file4']);

        /**
         * @var File $file3
         */
        $file3 = clone $files['file3'];
        $file3->setSize(10);
        $slaveCollection->addFile($file3);

        // Generate tasks
        $taskGenerator = new TaskGenerator();
        $taskGenerator->setSlavePathTpl('/path/to/{program}/{uid}');
        $tasks = $taskGenerator->handle($masterCollection, $slaveCollection);

        // Tasks check
        $this->assertCount(3, $tasks);
        $this->assertEquals('add', $tasks->get(0)->getName());
        $this->assertEquals('update', $tasks->get(1)->getName());
        $this->assertEquals('delete', $tasks->get(2)->getName());
    }

    /**
     * @expectedException \AppBundle\Exception\TaskException
     * @expectedExceptionCode \AppBundle\Exception\TaskException::SLAVE_PATH_NOT_SET
     */
    public function testNotSetPathTpl()
    {
        $taskGenerator = new TaskGenerator();
        $taskGenerator->getSlavePathTpl();
    }
}