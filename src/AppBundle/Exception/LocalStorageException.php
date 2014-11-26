<?php
namespace AppBundle\Exception;

/**
 * Exception for Local storage
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class LocalStorageException extends StorageException
{
    const FILE_NOT_FOUND = 10;
    const OPERATION_FAIL = 20;
}
