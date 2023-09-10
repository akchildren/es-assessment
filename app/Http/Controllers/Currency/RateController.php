<?php

namespace App\Http\Controllers\Currency;

use App\Currency\Adapter;
use App\Currency\CurrencyCodeParameters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Currency\CurrencyRateRequest;
use Illuminate\Http\JsonResponse;

class RateController extends Controller
{
    public function __construct(
        private readonly Adapter $adapter,
        private readonly CurrencyCodeParameters $parameters
    ) {
    }

    public function __invoke(CurrencyRateRequest $request): JsonResponse
    {
        $rate = $this->adapter->getRate(
            $this->parameters->getBaseCurrencyCode(),
            $this->parameters->getTargetCurrencyCode()
        );

        return response()->json(['data' => ['rate' => $rate]]);
    }
}
