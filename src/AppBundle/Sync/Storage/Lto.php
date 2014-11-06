<?php
namespace AppBundle\Sync\Storage;

use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\FileCollection;
use AppBundle\Exception\StorageException;
use DateTime;

class Lto extends AbstractStorage
{
    private $regList = "~(?P<size>[\d,]+) +B +(?P<date>\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}) +(?P<path>[^ ]+) +Never +Archive +Date: +(?P<archive_date>\d{2}\/\d{2}\/\d{2})~";

    /**
     * Put a file
     *
     * @param $sourcePath
     * @param $destPath
     */
    public function put($sourcePath, $destPath)
    {
        if (!is_file($sourcePath)) {
            throw new StorageException('File %s is missing', $sourcePath);
        }

        $this->delete($destPath);

        $command = sprintf('dsmc archive %s -v2archive', $destPath);
        $result  = shell_exec($command);

        if (strpos($result, 'finished without failure') === false) {
            throw new StorageException($result);
        }
    }

    /**
     * Delete a file
     *
     * @param $path
     */
    public function delete($path)
    {
        $command = sprintf('dsmc delete archive %s -noprompt', $path);
        $result  = shell_exec($command);

        if (strpos($result, 'finished without failure') === false) {
            throw new StorageException($result);
        }
    }

    /**
     * Collects data from lto
     *
     * @param string $directory
     *
     * @return FileCollection
     */
    public function listContents($directory = '')
    {
        $command = sprintf('dsmc query archive "%s/*" -subdir=yes', $directory);
        $result  = shell_exec($command);

        $fc = new FileCollection();

        $lines = explode("\n", $result);
        foreach ($lines as $line) {
            if (preg_match($this->regList, $line, $match)) {
                $size     = str_replace(',', '', $match['size']);
                $modified = DateTime::createFromFormat('m/d/Y H:i:s', $match['date']);

                $file = new File();

                $file->setUid(basename($match['path']));
                $file->setPath($match['path']);
                $file->setSize($size);
                $file->setModified($modified);

                $fc->addFile($file);
            }
        }

        return $fc;
    }
}