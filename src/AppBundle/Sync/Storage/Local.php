<?php
namespace AppBundle\Sync\Storage;

use AppBundle\Exception\StorageException;
use DateTime;
use FilesystemIterator;
use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\FileCollection;

class Local extends AbstractStorage
{
    /**
     * Put a file
     *
     * @param $sourcePath
     * @param $destPath
     */
    public function put($sourcePath, $destPath)
    {
        if (!is_file($sourcePath)) {
            throw new StorageException(sprintf('File %s not found', $sourcePath));
        }

        $this->ensureDirectory(dirname($destPath));

        $result = copy($sourcePath, $destPath);
        if (!$result) {
            throw new StorageException(sprintf('Copy failed: %s', $sourcePath));
        }
    }

    /**
     * Delete a file
     *
     * @param $path
     */
    public function delete($path)
    {
        if (!is_file($path)) {
            throw new StorageException(sprintf('File %s not found', $path));
        }

        $result = unlink($path);
        if (!$result) {
            throw new StorageException(sprintf('Delete failed: %s', $path));
        }
    }

    /**
     * Collects files from directory
     *
     * @param string $directory
     *
     * @return FileCollection
     */
    public function listContents($directory = '')
    {
        $flags    = FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS;

        $dirIterator  = new \RecursiveDirectoryIterator($directory, $flags);
        $fileIterator = new \RecursiveIteratorIterator($dirIterator);

        $fc = new FileCollection();

        /**
         * @var \SplFileInfo $rawFile
         */
        foreach ($fileIterator as $rawFile)
        {
            $modified = new DateTime('@' . $rawFile->getMTime());

            $file = new File();
            $file->setUid($rawFile->getBasename());
            $file->setPath($rawFile->getPathname());
            $file->setSize($rawFile->getSize());
            $file->setModified($modified);

            $fc->addFile($file);
        }

        return $fc;
    }

    /**
     * Ensure the directory exists.
     *
     * @param   string  $dir  Directory path
     * @return  string  Real path to dir
     */
    protected function ensureDirectory($dir)
    {
        if (!is_dir($dir)) {
            $result = mkdir($dir, 0755, true);
            if (!$result) {
                throw new StorageException(sprintf('Can\t create directory %s', $dir));
            }
        }

        return realpath($dir);
    }
}