<?php
namespace AppBundle\Sync\Entity;

use DateTime;

/**
 * File entity
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class File extends Entity
{
    /**
     * @var string  Unique id of the file
     */
    protected $uid;
    /**
     * @var string  File path
     */
    protected $path;
    /**
     * @var int  File size
     */
    protected $size;
    /**
     * @var DateTime Modification date
     */
    protected $modified;

    /**
     * @return DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param DateTime $modified
     */
    public function setModified(DateTime $modified)
    {
        $this->modified = $modified;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param string $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    /**
     * Serialize file to string
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            '%s, %sb, %s',
            $this->getPath(),
            number_format($this->getSize(), 0, '.', ' '),
            $this->getModified()->format('d.m.y H:i:s')
        );
    }
}
