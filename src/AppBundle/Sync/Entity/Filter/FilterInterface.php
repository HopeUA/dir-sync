<?php
namespace AppBundle\Sync\Entity\Filter;

use AppBundle\Sync\Entity\File;

interface FilterInterface
{
    public function valid(File $file);
}
