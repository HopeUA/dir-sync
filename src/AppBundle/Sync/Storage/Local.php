<?php
namespace AppBundle\Sync\Storage;

use AppBundle\Exception\LocalStorageException;
use DateTime;
use FilesystemIterator;
use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\FileCollection;

/**
 * Local app storage
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class Local implements StorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function put($sourcePath, $destPath)
    {
        if (!is_file($sourcePath)) {
            throw new LocalStorageException(
                sprintf('File %s not found', $sourcePath),
                LocalStorageException::FILE_NOT_FOUND
            );
        }

        $this->ensureDirectory(dirname($destPath));

        $result = @copy($sourcePath, $destPath);
        if (!$result) {
            throw new LocalStorageException(
                sprintf('Copy failed: %s', $sourcePath),
                LocalStorageException::OPERATION_FAIL
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path)
    {
        if (!is_file($path)) {
            throw new LocalStorageException(
                sprintf('File %s not found', $path),
                LocalStorageException::FILE_NOT_FOUND
            );
        }

        $result = unlink($path);
        if (!$result) {
            throw new LocalStorageException(
                sprintf('Delete failed: %s', $path),
                LocalStorageException::OPERATION_FAIL
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function listContents($directory = '')
    {
        $flags = FilesystemIterator::SKIP_DOTS | FilesystemIterator::UNIX_PATHS;

        try {
            $dirIterator  = new \RecursiveDirectoryIterator($directory, $flags);
        } catch (\UnexpectedValueException $e) {
            throw new LocalStorageException(
                sprintf('Directory missing: %s', $directory),
                LocalStorageException::FILE_NOT_FOUND
            );
        }
        $fileIterator = new \RecursiveIteratorIterator($dirIterator);

        $fc = new FileCollection();

        /**
         * @var \SplFileInfo $rawFile
         */
        foreach ($fileIterator as $rawFile) {
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
     *
     * @return  string  Real path to dir
     */
    protected function ensureDirectory($dir)
    {
        if (!is_dir($dir)) {
            $result = mkdir($dir, 0755, true);
            if (!$result) {
                throw new LocalStorageException(
                    sprintf('Can\'t create directory %s', $dir),
                    LocalStorageException::OPERATION_FAIL
                );
            }
        }

        return realpath($dir);
    }
}
