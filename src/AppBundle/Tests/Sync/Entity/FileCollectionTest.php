<?php
namespace AppBundle\Tests\Sync\Entity;

use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\FileCollection;
use MyProject\Proxies\__CG__\OtherProject\Proxies\__CG__\stdClass;

/**
 * File Collection tests
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class FileCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider fileProvider
     */
    public function testAdd($files)
    {
        // Create collection
        $fc = new FileCollection();
        $this->assertCount(0, $fc);

        // Add some files
        $fc->addFile($files[0]);
        $this->assertCount(1, $fc);

        $fc->addFile($files[1]);
        $this->assertCount(2, $fc);

        // Igrore adding the same file
        $fc->addFile($files[0]);
        $this->assertCount(2, $fc);
    }

    /**
     * @dataProvider fileProvider
     */
    public function testFilter($files)
    {
        $filter1 = $this->getMock('\\AppBundle\\Sync\\Entity\\Filter\\FilterInterface');
        $filter1
            ->expects($this->exactly(count($files)))
            ->method('valid')
            ->willReturn(true);
        $filter2 = $this->getMock('\\AppBundle\\Sync\\Entity\\Filter\\FilterInterface');
        $filter2
            ->expects($this->exactly(count($files)))
            ->method('valid')
            ->willReturn(true);

        $fc = new FileCollection();
        foreach ($files as $file) {
            $fc->addFile($file);
        }

        $fc->filter([$filter1, $filter2]);
    }

    /**
     * @dataProvider fileProvider
     */
    public function testIndex($files)
    {
        // Create collection
        $fc = new FileCollection();

        // Add some files
        $fc->addFile($files[0]);
        $fc->addFile($files[1]);
        $fc->addFile($files[0]);
        $fc->addFile($files[2]);
        $fc->del($files[0]->getPath());

        /**
         * Get from index
         *
         * @var File $file
         */
        $file = $files[2];
        $this->assertEquals($file, $fc->getByUid($file->getUid()));

        $fc->del($file->getPath());
        $this->assertNull($fc->getByUid($file->getUid()));
    }

    /**
     * @dataProvider fileProvider
     * @expectedException \AppBundle\Exception\FilterException
     */
    public function testBadFilter($files)
    {
        $fc = new FileCollection();
        foreach ($files as $file) {
            $fc->addFile($file);
        }

        $fc->filter(['bad filter']);
    }

    /**
     * @dataProvider fileProvider
     * @expectedException \AppBundle\Exception\FilterException
     */
    public function testBadFilterWithObject($files)
    {
        $fc = new FileCollection();
        foreach ($files as $file) {
            $fc->addFile($file);
        }

        $fc->filter([new \stdClass()]);
    }

    /**
     * @dataProvider fileProvider
     */
    public function testGetByUid($files)
    {
        /**
         * @var File $file
         */
        $file = $files[0];
        $fc   = new FileCollection();
        $fc->addFile($file);

        $this->assertEquals($file, $fc->getByUid($file->getUid()));
    }

    public function fileProvider()
    {
        $files = [];

        $file = new File();
        $file->setUid('TEST00113.mov');
        $file->setPath('/some/test/path/TEST00113.mov');
        $file->setModified(new \DateTime());
        $file->setSize(100);
        $files[] = $file;

        $file = new File();
        $file->setUid('TEST00313.mov');
        $file->setPath('/some/test2/path/TEST00313.mov');
        $file->setModified(new \DateTime());
        $file->setSize(200);
        $files[] = $file;

        $file = new File();
        $file->setUid('TEST00113.mov');
        $file->setPath('/some/test2/path/TEST00113.mov');
        $file->setModified(new \DateTime());
        $file->setSize(200);
        $files[] = $file;

        return [[$files]];
    }
}
