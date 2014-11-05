<?php
namespace AppBundle\Sync\Entity\Filter;

use AppBundle\Sync\Entity\File;

class Path implements FilterInterface
{
    protected $pattern;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public function valid(File $file)
    {
        return preg_match($this->pattern, $file->getPath());
    }
}