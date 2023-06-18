<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */

    //  ini ketik saya ingin vardump di bagian submit untuk create data
    // fungsinya saya ingin melihat array
    protected $except = [
        '/transactions',
    ];


}
