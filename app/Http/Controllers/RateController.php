<?php

namespace App\Http\Controllers;

use App\Actions\GetCurrencyCodeParameters;
use App\Currency\Adapter;
use App\Http\Requests\CurrencyRateRequest;
use Illuminate\Http\JsonResponse;

class RateController extends Controller
{
    /**
     * @param Adapter $adapter
     * @param GetCurrencyCodeParameters $parameters
     */
    public function __construct(
        private readonly Adapter $adapter,
        private readonly GetCurrencyCodeParameters $parameters
    )
    {
    }

    /**
     * @param CurrencyRateRequest $request
     * @return JsonResponse
     */
    public function __invoke(CurrencyRateRequest $request): JsonResponse
    {
        $rate = $this->adapter->getRate(
            $this->parameters->getBaseCurrencyCode(),
            $this->parameters->getTargetCurrencyCode()
        );

        return response()->json(['data' => ['rate' => $rate]]);
    }
}
