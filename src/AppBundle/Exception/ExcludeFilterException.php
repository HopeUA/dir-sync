<?php
namespace AppBundle\Exception;

/**
 * Exception for Exclude filter
 *
 * @author Sergey Sadovoi <serg.sadovoi@gmail.com>
 */
class ExcludeFilterException extends FilterException
{
    const MISSING_FILE   = 10;
    const INVALID_JSON   = 20;
    const NOT_ARRAY      = 30;
    const UNDEFINED_PATH = 40;
}
