<?php

namespace App\Core\Exceptions;

use App\Core\Enums\HttpCodes;

class InvalidParametersException extends \Exception
{
    public function __construct($message = "Los parámetros enviados son inválidos", $code = HttpCodes::BadRequest)
    {
        parent::__construct($message, $code);
    }
}