<?php 

namespace App\Core\Exceptions;

use App\Core\Enums\HttpCodes;

class NotFoundException extends \Exception
{
    public function __construct($message = "Recurso no encontrado", $code = HttpCodes::NotFound)
    {
        parent::__construct($message, $code);
    }
}