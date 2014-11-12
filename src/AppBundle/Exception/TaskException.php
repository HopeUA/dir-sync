<?php
namespace AppBundle\Exception;

class TaskException extends \RuntimeException
{
    const SLAVE_PATH_NOT_SET = 10;
    const INVALID_TASK       = 20;
}
