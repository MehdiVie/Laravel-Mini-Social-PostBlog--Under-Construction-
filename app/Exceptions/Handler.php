<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception): \Symfony\Component\HttpFoundation\Response
    {
        return parent::render($request, $exception);
        //die('here'); // Debugging to verify the render method is triggered
    }

    public function report(Throwable $exception): void
    {
        \Log::info('Custom Handler Report Called');
        parent::report($exception);
    }
}
