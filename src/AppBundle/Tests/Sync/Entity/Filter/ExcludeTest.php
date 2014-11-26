<?php
namespace AppBundle\Tests\Sync\Entity\Filter;

use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\Filter\Exclude;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Debug\ErrorHandler;

class ExcludeTest extends \PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $files    = ['TEST00113.mov', 'TEST00213.mov', 'TEST00313.mov'];
        $excludes = [$files[0], $files[1]];
        $tree     = ['exclude.list' => json_encode($excludes)];

        // Init virtual FS
        vfsStream::setup('root', null, $tree);

        $filter = new Exclude();
        $filter->setPath(vfsStream::url('root/exclude.list'));

        foreach ($files as $fileName) {
            $file = new File();
            $file->setUid($fileName);

            $valid = (array_search($fileName, $excludes) === false);
            $this->assertEquals($valid, $filter->valid($file));
        }
    }

    /**
     * @expectedException \AppBundle\Exception\ExcludeFilterException
     * @expectedExceptionCode \AppBundle\Exception\ExcludeFilterException::UNDEFINED_PATH
     */
    public function testUnsetPath()
    {
        $filter = new Exclude();
        $filter->valid(new File());
    }

    /**
     * @expectedException \AppBundle\Exception\ExcludeFilterException
     * @expectedExceptionCode \AppBundle\Exception\ExcludeFilterException::MISSING_FILE
     */
    public function testMissingFile()
    {
        $filter = new Exclude();
        $filter->setPath('/missing/path');

        $filter->valid(new File());
    }

    /**
     * @expectedException \AppBundle\Exception\ExcludeFilterException
     * @expectedExceptionCode \AppBundle\Exception\ExcludeFilterException::INVALID_JSON
     */
    public function testInvalidContent()
    {
        $tree = ['exclude.list' => 'bad data'];

        // Init virtual FS
        vfsStream::setup('root', null, $tree);

        $filter = new Exclude();
        $filter->setPath(vfsStream::url('root/exclude.list'));

        $filter->valid(new File());
    }

    /**
     * @expectedException \AppBundle\Exception\ExcludeFilterException
     * @expectedExceptionCode \AppBundle\Exception\ExcludeFilterException::NOT_ARRAY
     */
    public function testContentNotArray()
    {
        $tree = ['exclude.list' => '"bad data"'];

        // Init virtual FS
        vfsStream::setup('root', null, $tree);

        $filter = new Exclude();
        $filter->setPath(vfsStream::url('root/exclude.list'));

        $filter->valid(new File());
    }
}
