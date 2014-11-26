<?php
namespace AppBundle\Sync\Storage;

use AppBundle\Sync\Entity\FileCollection;
use AppBundle\Exception\StorageException;

/**
 * Interface for app storage
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
interface StorageInterface
{
    /**
     * Get directory contents
     *
     * @param string $directory
     *
     * @return FileCollection
     *
     * @throws StorageException
     */
    public function listContents($directory = '');

    /**
     * Put file from source to dest
     *
     * @param string $sourcePath
     * @param string $destPath
     *
     * @throws StorageException
     */
    public function put($sourcePath, $destPath);

    /**
     * Delete the file
     *
     * @param string $path  File path
     *
     * @throws StorageException
     */
    public function delete($path);
}
