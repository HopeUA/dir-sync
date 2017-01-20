<?php
namespace Tests\AppBundle\Sync\Entity\Filter;

use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\Filter\ExcludeShows;
use org\bovigo\vfs\vfsStream;

/**
 * Exclude Shows filter tests
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class ExcludeShowsTest extends \PHPUnit_Framework_TestCase
{
    public function testFilter()
    {
        $files    = ['TEST00113.mov', 'AAAA00113.mov'];
        $excludes = ['AAAA'];
        $tree     = ['exclude.list' => json_encode($excludes)];

        // Init virtual FS
        vfsStream::setup('root', null, $tree);

        $filter = new ExcludeShows();
        $filter->setPath(vfsStream::url('root/exclude.list'));

        foreach ($files as $fileName) {
            $file = new File();
            $file->setUid($fileName);

            $valid = (array_search(substr($fileName, 0, 4), $excludes) === false);
            $this->assertEquals($valid, $filter->valid($file));
        }
    }

    /**
     * @expectedException \AppBundle\Exception\ExcludeFilterException
     * @expectedExceptionCode \AppBundle\Exception\ExcludeFilterException::UNDEFINED_PATH
     */
    public function testUnsetPath()
    {
        $filter = new ExcludeShows();
        $filter->valid(new File());
    }

    /**
     * @expectedException \AppBundle\Exception\ExcludeFilterException
     * @expectedExceptionCode \AppBundle\Exception\ExcludeFilterException::MISSING_FILE
     */
    public function testMissingFile()
    {
        $filter = new ExcludeShows();
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

        $filter = new ExcludeShows();
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

        $filter = new ExcludeShows();
        $filter->setPath(vfsStream::url('root/exclude.list'));

        $filter->valid(new File());
    }
}
