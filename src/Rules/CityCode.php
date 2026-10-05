<?php

namespace FLAIRUK\Cities\Rules;

use Closure;
use FLAIRUK\Cities\Cities;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Validates that the value is a known IATA city designator.
 */
class CityCode implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! app(Cities::class)->exists($value)) {
            $fail('The :attribute must be a valid IATA city code.');
        }
    }
}
