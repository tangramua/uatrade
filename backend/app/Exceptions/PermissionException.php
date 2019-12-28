<?php
namespace App\Exceptions;


use Exception;
use Throwable;

class PermissionException extends Exception
{
    public function __construct(string $message = "", int $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}