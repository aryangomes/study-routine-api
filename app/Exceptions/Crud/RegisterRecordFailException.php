<?php

namespace App\Exceptions\Crud;

use Exception;

class RegisterRecordFailException extends Exception
{

    protected string $message = "Register this record fails!";
    protected string $code = 400;

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }
}
