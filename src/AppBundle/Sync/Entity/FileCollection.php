<?php
namespace AppBundle\Sync\Entity;

use AppBundle\Exception\FilterException;
use AppBundle\Sync\Entity\Filter\FilterInterface;

class FileCollection extends Collection
{
    /**
     * @var array  Index of files by uid
     */
    protected $index = [];

    /**
     * Adds file to the collection
     *
     * @param File $file
     */
    public function addFile(File $file)
    {
        $this->add($file, $file->getPath());
    }

    /**
     * Filter the collection
     *
     * @param array $filters Array of Filter objects
     */
    public function filter(array $filters)
    {
        foreach ($this->items as $key => $item) {
            foreach ($filters as $filter) {
                if (!($filter instanceof FilterInterface)) {
                    $type = gettype($filter);
                    if ($type == 'object') {
                        $type = get_class($filter);
                    }

                    throw new FilterException(sprintf('Filter must be an instance of Filter class, %s given', $type));
                }

                if (!$filter->valid($item)) {
                    $this->del($key);
                    break;
                }
            }
        }
    }

    /**
     * Add file and update index
     *
     * @param File $file
     * @param null $key
     */
    public function add(File $file, $key = null) {
        $this->addToIndex($file);

        parent::add($file, $key);
    }

    /**
     * Del file and update index
     *
     * @param $key
     */
    public function del($key)
    {
        $file = $this->get($key);
        if (!is_null($file)) {
            $this->deleteFromIndex($file);
        }

        parent::del($key);
    }

    /**
     * Search the file using index
     *
     * @param string $uid  Unique id of file
     *
     * @return null|File
     */
    public function getByUid($uid) {
        if (!isset($this->index[$uid])) {
            return null;
        }

        $files = $this->index[$uid];
        if (count($files) == 0) {
            return null;
        }

        $key = array_pop($files);
        return $this->get($key);
    }

    /**
     * Add file to index for searching
     *
     * @param File $file
     */
    protected function addToIndex(File $file)
    {
        $uid = $file->getUid();

        if (isset($this->index[$uid])) {
            foreach ($this->index[$uid] as $path) {
                if ($file->getPath() == $path) {
                    return;
                }
            }
        }

        $this->index[$uid][] = $file->getPath();
    }

    /**
     * Delete file from index
     *
     * @param File $file
     *
     * @return bool
     */
    protected function deleteFromIndex(File $file)
    {
        if (!isset($this->index[$file->getUid()])) {
            return true;
        }

        // Remove element from index
        $index =& $this->index[$file->getUid()];
        foreach ($index as $key => $path) {
            if ($path == $file->getPath()) {
                unset($index[$key]);
            }
        }

        // Remove empty index
        if (count($index) == 0) {
            unset($this->index[$file->getUid()]);
        }

        return true;
    }
}