<?php
namespace AppBundle\Sync\Entity\Filter;

use AppBundle\Sync\Entity\File;

/**
 * Filter files by list of excluded episodes
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class ExcludeEpisodes extends Exclude
{
    /**
     * {@inheritdoc}
     */
    public function valid(File $file)
    {
        if (!is_array($this->elements)) {
            $this->load();
        }

        return (false === array_search($file->getUid(), $this->elements));
    }
}
