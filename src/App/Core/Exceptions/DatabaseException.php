<?php

namespace App\Core\Exceptions;

use App\Core\Enums\HttpCodes;

class DatabaseException extends \Exception
{
    public function __construct($message = "Error en la base de datos", $code = HttpCodes::InternalServerError)
    {
        parent::__construct($message, $code);
    }
}