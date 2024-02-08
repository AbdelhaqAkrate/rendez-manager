<?php

namespace App\Exceptions\Auth;

use Exception;
use Illuminate\Http\Request;
use Throwable;

class UserNotLoggedInException extends Exception
{
    public function __construct(?string $message = null, int $code = 0, ?Throwable $previous = null)
    {
        if (!$message) {
            $message = "User not logged in";
        }

        parent::__construct($message, $code, $previous);
    }
}
