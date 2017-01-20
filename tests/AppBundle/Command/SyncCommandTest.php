<?php
namespace Tests\AppBundle\Command;

use AppBundle\Command\SyncCommand;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use org\bovigo\vfs\vfsStream;

/**
 * Sync Command tests
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class SyncCommandTest extends WebTestCase
{
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    public function testExecute()
    {
        // Set up virtual FS
        $fs = vfsStream::setup('root', null, $this->getDirTree());

        $app = new Application(static::$kernel);
        $command = new SyncCommand();
        $command->setApplication($app);

        $tester  = new CommandTester($command);
        $tester->execute(['command' => $command->getName()]);

        // Check result directory
        $this->assertTrue($fs->hasChild('dest/TEST/TEST00113.mov'));
        $this->assertTrue($fs->hasChild('dest/TEST/TEST00213.mov'));
        $this->assertTrue($fs->hasChild('dest/TEST/TEST00313.mov'));
        $this->assertFalse($fs->hasChild('dest/TEST/TEST00413.mov'));
        $this->assertFalse($fs->hasChild('dest/TEST/TEST00513.mov'));
        $this->assertTrue($fs->hasChild('dest/ZZZZ/ZZZZ00113.mov'));
        $this->assertFalse($fs->hasChild('dest/QQQQ/QQQQ00113.mov'));
    }

    protected function getDirTree()
    {
        return [
            'source' => [
                'TEST' => [
                    'stream' => [
                        'TEST00113.mov' => 'test stream content',
                        'TEST00213.mov' => 'test stream content',
                        'TEST00313.mov' => 'test stream content',
                        'TEST00513.mov' => 'test stream content',
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
                ],
                'QQQQ' => [
                    'stream' => [
                        'QQQQ00113.mov' => 'test stream content',
                    ],
                    'source' => [
                        'QQQQ00313.mov' => 'test source content',
                    ],
                ],
            ],
            'dest' => [
                'TEST' => [
                    'TEST00213.mov' => 'test stream content',
                    'TEST00313.mov' => 'test stream content 2',
                    'TEST00413.mov' => 'test stream content 2',
                ],
            ],
            'exclude-episodes.json' => '["TEST00513.mov"]',
            'exclude-shows.json'    => '["QQQQ"]',
        ];
    }
}
