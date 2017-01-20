<?php
namespace AppBundle\Sync\Entity\Filter;

use AppBundle\Exception\ExcludeFilterException;
use AppBundle\Sync\Entity\File;

/**
 * Filter files by list of excluded elements
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class ExcludeShows implements FilterInterface
{
    /**
     * @var string  Path to file with excludes
     */
    protected $path;
    /**
     * @var array  List of ids for exclude
     */
    protected $elements;

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

    /**
     * Load the array of excluded items and save them to $elements
     *
     * @throws ExcludeFilterException
     */
    protected function load()
    {
        $result = @file_get_contents($this->getPath());

        if (false === $result) {
            throw new ExcludeFilterException(
                sprintf('[ExludeFilter] Error while loading file %s', $this->getPath()),
                ExcludeFilterException::MISSING_FILE
            );
        }

        $result = json_decode($result);
        if (is_null($result)) {
            throw new ExcludeFilterException(
                sprintf('[ExludeFilter] Error while loading exclude data from %s', $this->getPath()),
                ExcludeFilterException::INVALID_JSON
            );
        }
        if (!is_array($result)) {
            throw new ExcludeFilterException(
                sprintf('[ExludeFilter] Invalid result type "%s" for exclude data, must be an array', gettype($result)),
                ExcludeFilterException::NOT_ARRAY
            );
        }

        $this->elements = $result;
    }

    /**
     * Get the path to file with excludes
     *
     * @throws ExcludeFilterException
     *
     * @return string File path
     */
    public function getPath()
    {
        $path = $this->path;
        if ('' == $path) {
            throw new ExcludeFilterException(
                '[ExcludeFilter] Path must be defined',
                ExcludeFilterException::UNDEFINED_PATH
            );
        }

        return $this->path;
    }

    /**
     * Set the path to file with excludes
     *
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}
