<?php
namespace AppBundle\Sync\Storage;

use AppBundle\Sync\Entity\FileCollection;

abstract class AbstractStorage implements StorageInterface
{
    /**
     * @param string $directory
     *
     * @return FileCollection
     */
    abstract public function listContents($directory = '');
    abstract public function put($sourcePath, $destPath);
    abstract public function delete($path);
}