<?php

namespace App\Http\Controllers\Currency;

use App\Actions\Currency\CurrencyCodeParameters;
use App\Currency\Contract;
use App\Http\Controllers\Controller;
use App\Http\Requests\Currency\CurrencyRateRequest;
use Illuminate\Http\JsonResponse;

class CurrencyRateController extends Controller
{
    public function __construct(
        private readonly Contract $contract,
        private readonly CurrencyCodeParameters $parameters
    ) {
    }

    public function __invoke(CurrencyRateRequest $request): JsonResponse
    {
        $rate = $this->contract->getRate(
            $this->parameters->getBaseCurrencyCode(),
            $this->parameters->getTargetCurrencyCode()
        );

        return response()->json(['data' => ['rate' => $rate]]);
    }
}
