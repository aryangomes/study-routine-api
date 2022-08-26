<?php

namespace App\Support\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Log;

class CustomModelNotFoundException extends Exception
{
    public function __construct(private \Exception $exception)
    {
    }



    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        Log::error(
            get_class($this),
            [
                'url' => $request->url(),
                'exception message' => $this->exception->getMessage(),
            ]
        );

        return response()
            ->json(
                ['response' =>
                'Results for this model not found!'],
                Response::HTTP_NOT_FOUND
            );
    }
}
