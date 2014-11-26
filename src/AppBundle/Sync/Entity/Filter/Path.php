<?php
namespace AppBundle\Sync\Entity\Filter;

use AppBundle\Sync\Entity\File;

/**
 * Filter files by path
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class Path implements FilterInterface
{
    /**
     * @var string  Regex pattern for file path
     */
    protected $pattern;

    /**
     * {@inheritdoc}
     */
    public function valid(File $file)
    {
        return preg_match($this->pattern, $file->getPath());
    }

    /**
     * Set the file path pattern
     * @param string $pattern  Regex pattern for file path
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }
}
