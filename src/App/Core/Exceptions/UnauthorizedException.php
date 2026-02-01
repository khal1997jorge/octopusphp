<?php

namespace App\Core\Exceptions;

use App\Core\Enums\HttpCodes;

class UnauthorizedException extends \Exception
{
    public function __construct($message = "No autorizado", $code = HttpCodes::Unauthorized)
    {
        parent::__construct($message, $code);
    }
}