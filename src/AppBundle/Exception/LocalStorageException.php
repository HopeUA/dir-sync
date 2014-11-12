<?php
namespace AppBundle\Exception;

class LocalStorageException extends StorageException
{
    const FILE_NOT_FOUND = 10;
    const OPERATION_FAIL = 20;
}
