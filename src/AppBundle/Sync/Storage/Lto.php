<?php
namespace AppBundle\Sync\Storage;

use AppBundle\Sync\Entity\File;
use AppBundle\Sync\Entity\FileCollection;
use AppBundle\Exception\StorageException;
use DateTime;

/**
 * LTO app storage
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class Lto implements StorageInterface
{
    /**
     * @var string  Regex pattern for file list
     */
    private $regList;

    /**
     * Init the regList pattern
     */
    public function __construct()
    {
        $regExp = "~(?P<size>[\d,]+) +B +(?P<date>\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}:\d{2}) ".
            "+(?P<path>[^ ]+) +Never +Archive +Date: +(?P<archive_date>\d{2}\/\d{2}\/\d{2})~";

        $this->regList = $regExp;
    }

    /**
     * {@inheritdoc}
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
            throw new StorageException(
                sprintf('Archive error while processing command "%s". Server response: %s', $command, $result)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path)
    {
        $command = sprintf('dsmc delete archive %s -noprompt', $path);
        $result  = shell_exec($command);

        if (strpos($result, 'No objects on server match query')            === false &&
            strpos($result, 'Total number of objects deleted:          1') === false
        ) {
            throw new StorageException(
                sprintf('Delete error while processing command "%s". Server response: %s', $command, $result)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function listContents($directory = '')
    {
        $command = sprintf('dsmc query archive "%s/*" -subdir=yes', $directory);
        $result  = shell_exec($command);

        $fc = new FileCollection();

        if (strpos($result, 'No files matching search criteria were found') !== false) {
            return $fc;
        }

        $lines = explode("\n", $result);
        foreach ($lines as $line) {
            if (preg_match($this->regList, $line, $match)) {
                $size     = str_replace(',', '', $match['size']);
                $modified = DateTime::createFromFormat('m/d/Y H:i:s', $match['date']);

                if (false === $modified) {
                    continue;
                }

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
