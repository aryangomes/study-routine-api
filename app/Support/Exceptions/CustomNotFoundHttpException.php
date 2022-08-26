<?php

namespace App\Support\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;

class CustomNotFoundHttpException extends Exception
{

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {

        Log::error(
            get_class($this),
            ['url' => $request->url()]
        );
        return response()->json(
            ['message' => "Sorry, but we don't found what you were searching for!"],
            Response::HTTP_NOT_FOUND
        );
    }
}
