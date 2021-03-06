<?php
namespace Tests\AppBundle\Sync\Entity;

/**
 * Collection tests
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testInit()
    {
        /**
         * @var \AppBundle\Sync\Entity\Collection $collection
         */
        $collection = $this->getMockForAbstractClass('\\AppBundle\\Sync\\Entity\\Collection');
        $this->assertEquals(0, $collection->count());

        $this->assertEquals(0, $collection->key());
    }
}
