<?php
namespace AppBundle\Exception;

class ExcludeFilterException extends FilterException
{
    const MISSING_FILE   = 10;
    const INVALID_JSON   = 20;
    const NOT_ARRAY      = 30;
    const UNDEFINED_PATH = 40;
}