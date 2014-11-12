<?php
namespace AppBundle\Sync\Entity\Filter;

use AppBundle\Exception\ExcludeFilterException;
use AppBundle\Sync\Entity\File;

/**
 * Filter files by list of excluded elements
 */
class Exclude implements FilterInterface
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
        if (is_null($this->elements)) {
            $this->load();
        }

        return (false === array_search($file->getUid(), $this->elements));
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
                sprintf('[ExludeFilter] Error while loading file %s', $result),
                ExcludeFilterException::MISSING_FILE
            );
        }

        $result = json_decode($result);
        if (is_null($result)) {
            throw new ExcludeFilterException(
                sprintf('[ExludeFilter] Error while loading exclude data from %s', $result),
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
     * @throws ExcludeFilterException
     *
     * @return string
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
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}
