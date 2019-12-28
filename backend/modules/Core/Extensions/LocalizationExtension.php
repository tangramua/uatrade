<?php
namespace Modules\Core\Extensions;


use Exception;
use Throwable;

class LocalizationExtension extends Exception
{

    public function __construct(string $message= "Localization error", int $code = 422, Throwable $previous = null)
    {
        if ($message)
        parent::__construct($message, $code, $previous);

    }

}