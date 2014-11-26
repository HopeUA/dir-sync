<?php
namespace AppBundle\Sync\Entity\Filter;

use AppBundle\Sync\Entity\File;

/**
 * Interface for filters
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
interface FilterInterface
{
    /**
     * Check if File is pass the filter
     *
     * @param File $file
     *
     * @return mixed
     */
    public function valid(File $file);
}
