<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Translation\PotentiallyTranslatedString;

class CurrencyCode implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validator = Validator::make([$attribute => $value],
            [
                $attribute => 'required|string|exists:currency_rates,parent_currency_code|max:3'
            ]
        );

        if ($validator->fails()) {
            $fail($validator->errors()->first());
        }
    }
}