<?php
namespace AppBundle\Exception;

/**
 * Exception for Tasks
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class TaskException extends \RuntimeException
{
    const SLAVE_PATH_NOT_SET = 10;
    const INVALID_TASK       = 20;
}
