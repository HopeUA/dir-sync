<?php
namespace AppBundle\Tests\Sync\Entity\Filter;

use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\Filter\Path;

class PathTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider pathProvider
     */
    public function testFilter($path, $valid)
    {
        $pattern = '~.*\/XYZ\/.*\.mov~';

        $file = new File();
        $file->setPath($path);

        $filter = new Path();
        $filter->setPattern($pattern);
        $this->assertEquals($valid, $filter->valid($file));
    }

    public function pathProvider()
    {
        return [
            ['/test/ABC/test.mov', false],
            ['/test/XYZ/test.mov', true],
            ['/test/XYZ/test.jpg', false],
        ];
    }
}
