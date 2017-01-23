<?php
namespace Tests\AppBundle\Mock;

class FileCollection extends \AppBundle\Sync\Entity\FileCollection
{
    public function clearIndexKeys()
    {
        foreach ($this->index as $key => &$value) {
            $value = [];
        }
    }

    public function clearIndex()
    {
        $this->index = [];
    }
}
