<?php

namespace App\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    // You can add URLs to exclude from CSRF protection if needed.
    protected $except = [
        //
    ];
}
