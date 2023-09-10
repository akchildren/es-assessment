<?php

namespace App\Actions;

use Illuminate\Http\Request;

readonly class GetCurrencyCodeParameters
{
    /**
     * @param Request $request
     */
    public function __construct(
        private Request $request
    )
    {
    }

    /**
     * @return string
     */
    public function getBaseCurrencyCode(): string
    {
        return $this->request->get('base') ?? $this->request->header('X-Base-Currency');
    }

    /**
     * @return string
     */
    public function getTargetCurrencyCode(): string
    {
        return (string)$this->request->get('currency');
    }
}
