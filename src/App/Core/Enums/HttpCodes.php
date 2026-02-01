<?php

namespace App\Core\Enums;

class HttpCodes {
    const Ok = 200;
    const Created = 201;
    const BadRequest = 400;
    const Unauthorized = 401;
    const NotFound = 404;
    const BadMethodCallException = 405;
    const InternalServerError = 500;
}