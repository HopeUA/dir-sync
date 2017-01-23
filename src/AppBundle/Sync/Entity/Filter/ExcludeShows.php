<?php
namespace AppBundle\Sync\Entity\Filter;

use AppBundle\Sync\Entity\File;

/**
 * Filter files by list of excluded shows
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class ExcludeShows extends Exclude
{
    /**
     * {@inheritdoc}
     */
    public function valid(File $file)
    {
        if (!is_array($this->elements)) {
            $this->load();
        }

        return (false === array_search(substr($file->getUid(), 0, 4), $this->elements));
    }
}
