<?php

namespace App\Http\Middleware\Currency;

use App\Rules\Currency\CurrencyCode;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ValidateBaseRateHeader
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validator = Validator::make(['X-Base-Currency' => $request->header('X-Base-Currency')],
            [
                'X-Base-Currency' => [new CurrencyCode]
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->all(), 404);
        }

        return $next($request);
    }
}
