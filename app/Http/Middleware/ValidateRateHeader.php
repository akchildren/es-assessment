<?php

namespace App\Http\Middleware;

use App\Exceptions\XBaseCurrencyException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateRateHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     *
     * @throws XBaseCurrencyException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('X-Base-Currency')) {
            return $next($request);
        }

        throw new XBaseCurrencyException();
    }
}
