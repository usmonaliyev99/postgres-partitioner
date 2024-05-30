<?php

namespace Usmonaliyev\PostgresPartitioner\Exceptions;

use Exception;

class UnsupportedFormatException extends Exception
{
    protected $message = 'Unsupported format';
}
