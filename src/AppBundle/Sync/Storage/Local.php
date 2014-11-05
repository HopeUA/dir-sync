<?php
namespace AppBundle\Sync\Storage;

use DateTime;
use FilesystemIterator;
use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\FileCollection;

class Local extends AbstractStorage
{
    /**
     * @param $sourcePath
     * @param $destPath
     *
     * @return bool
     */
    public function put($sourcePath, $destPath)
    {
        if (!is_file($sourcePath)) {
            return false;
        }

        $this->ensureDirectory(dirname($destPath));

        return copy($sourcePath, $destPath);
    }

    /**
     * Delete a file
     *
     * @param $path
     * @return bool
     */
    public function delete($path)
    {
        if (!is_file($path)) {
            return false;
        }

        return unlink($path);
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
            mkdir($dir, 0755, true);
        }

        return realpath($dir);
    }
}