<?php
namespace AppBundle\Sync\Entity;

use DateTime;

class File extends Entity
{
    protected $uid;
    protected $path;
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
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @param mixed $uid
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
    }

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
