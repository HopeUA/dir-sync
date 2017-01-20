<?php
namespace Tests\AppBundle\Sync;

use AppBundle\Sync\Entity\Filter\Path;
use AppBundle\Sync\Storage\Local;
use AppBundle\Sync\Sync;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\visitor\vfsStreamStructureVisitor;

/**
 * Sync tests
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class SyncTest extends \PHPUnit_Framework_TestCase
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
                'TEST' => [
                    'stream' => [
                        'TEST00213.mov' => 'test stream content',
                        'TEST00313.mov' => 'test stream content',
                    ],
                    'source' => [
                        'TEST00213.mov' => 'test source content',
                    ],
                ],
                'ZZZZ' => [
                    'stream' => [
                        'ZZZZ00113.mov' => 'test stream content',
                    ],
                    'source' => [
                        'ZZZZ00313.mov' => 'test source content',
                    ],
                ]
            ],
            'dest' => [
                'TEST' => [
                    'TEST00213.mov' => 'test stream content',
                    'TEST00313.mov' => 'test stream content 2',
                    'TEST00413.mov' => 'test stream content 2',
                ],
            ]
        ];
        // Init virtual FS
        $this->root = vfsStream::setup('root', null, $tree);
    }

    public function testDirSync()
    {
        $sync = new Sync();

        // Set up Master
        $pathFilter = new Path();
        $pathFilter->setPattern('~[A-Z]{4}/stream/.*\.mov~');

        $masterStorage = new Local();
        $masterPath    = 'root/source';
        $masterFilters = [$pathFilter];

        $sync->setMasterStorage($masterStorage);
        $sync->setMasterPath(vfsStream::url($masterPath));
        $sync->setMasterFilters($masterFilters);

        // Set up Slave
        $slaveStorage = new Local();
        $slavePath    = 'root/dest';
        $slavePathTpl = $slavePath . '/__program__/__uid__';

        $sync->setSlaveStorage($slaveStorage);
        $sync->setSlavePath(vfsStream::url($slavePath));
        $sync->setSlavePathTpl(vfsStream::url($slavePathTpl));

        // Run
        $sync->run();

        // Test
        $files = [
            'root/dest/TEST/TEST00213.mov' => 'test stream content',
            'root/dest/TEST/TEST00313.mov' => 'test stream content',
            'root/dest/ZZZZ/ZZZZ00113.mov' => 'test stream content',
        ];
        $removed = 'root/dest/TEST/TEST00413.mov';

        foreach ($files as $path => $content) {
            $this->assertTrue($this->root->hasChild($path));
            $this->assertEquals($content, $this->root->getChild($path)->getContent());
        }

        $this->assertFalse($this->root->hasChild($removed));
    }
}
