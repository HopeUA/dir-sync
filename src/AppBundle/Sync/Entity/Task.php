<?php
namespace AppBundle\Sync\Entity;

abstract class Task extends Entity
{
    protected $name;

    public function getName()
    {
        return $this->name;
    }

    abstract public function getMessageSuccess();
}