<?php

declare(strict_types=1);

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait LogException
{
    public function logException(Exception $exception)
    {

        $className = $this::class;
        $class = "Exception thrown in $className class";

        $context = ['exception' => $exception];

        Log::debug(
            $class,
            $context
        );
    }
}
