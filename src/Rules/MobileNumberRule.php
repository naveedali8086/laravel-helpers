<?php

namespace Naveedali8086\LaravelHelpers\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MobileNumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Validating mobile number that meets following conditions:
        // 1. must start with '+' sign
        // 2. must have country code
        // 3. must have 10 digits after country code
        if (!preg_match('/^(\+\d{1,4}\d{10})$/', $value)) {
            $fail("The :attribute must be a valid mobile number");
        }
    }
}
