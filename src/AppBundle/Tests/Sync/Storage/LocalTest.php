<?php
namespace AppBundle\Tests\Sync\Storage;

use AppBundle\Sync\Storage\Local;
use AppBundle\Sync\Entity\File;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;

class LocalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Root directory of virtual FS
     *
     * @var vfsStreamDirectory
     */
    protected $root;

    public function setUp()
    {
        $tree = [
            'source' => [
                'ADUU' => [
                    'stream' => [
                        'ADUU00313.mov' => 'test stream content',
                    ],
                    'source' => [
                        'ADUU00213.mov' => 'test source content',
                    ],
                ],
                'BCUU' => [
                    'stream' => [
                        'BCUU00113.mov' => 'test stream content',
                    ],
                    'source' => [
                        'BCUU00313.mov' => 'test source content',
                    ],
                ]
            ],
            'dest' => [
                'ADUU' => [
                    'stream' => [
                        'ADUU00313.mov' => 'test stream content 2',
                    ],
                    'source' => [
                        'ADUU00213.mov' => 'test source content 2',
                    ],
                ],
            ]
        ];
        // Init virtual FS
        $this->root = vfsStream::setup('root', null, $tree);
    }

    public function testPut()
    {
        $source1 = 'root/source/ADUU/stream/ADUU00313.mov';
        $dest1   = 'root/dest/ADUU/stream/ADUU00313.mov';
        $source2 = 'root/source/BCUU/stream/BCUU00113.mov';
        $dest2   = 'root/dest/BCUU/stream/BCUU00113.mov';

        $storage = new Local();

        // Replace existing file
        $storage->put(vfsStream::url($source1), vfsStream::url($dest1));
        $this->assertTrue($this->root->hasChild($dest1));

        // Create new file and dir
        $storage->put(vfsStream::url($source2), vfsStream::url($dest2));
        $this->assertTrue($this->root->hasChild($dest2));
    }

    /**
     * @expectedException \AppBundle\Exception\StorageException
     */
    public function testFailedPut()
    {
        $source3 = 'root/source/UUUU/stream/BCUU00113.mov';
        $dest3   = 'root/dest/UUUU/stream/BCUU00113.mov';

        $storage = new Local();
        $storage->put(vfsStream::url($source3), vfsStream::url($dest3));
    }

    public function testDelete()
    {
        $storage = new Local();

        $dest1 = 'root/dest/ADUU/stream/ADUU00313.mov';
        $storage->delete(vfsStream::url($dest1));
        $this->assertFalse($this->root->hasChild($dest1));
    }

    /**
     * @expectedException \AppBundle\Exception\StorageException
     */
    public function testFailedDelete()
    {
        $storage = new Local();

        $dest2 = 'root/dest/BCUU/stream/BCUU00113.mov';
        $storage->delete(vfsStream::url($dest2));
    }

    public function testList()
    {
        $sourceDir  = 'root/source';
        $sourceFile = 'root/source/ADUU/stream/ADUU00313.mov';

        $storage    = new Local();

        $collection = $storage->listContents(vfsStream::url($sourceDir));
        $this->assertCount(4, $collection);

        /**
         * @var File $file
         */
        $file = $collection->get(vfsStream::url($sourceFile));
        $this->assertEquals('ADUU00313.mov', $file->getUid());
    }
}