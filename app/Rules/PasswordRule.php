<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) < 4) {
            $fail('The :attribute must be at least 8 characters.');
        }

        // if (!preg_match('/[A-Z]/', $value)) {
        //     $fail('The :attribute must contain at least one uppercase letter.');
        // }

        // if (!preg_match('/[a-z]/', $value)) {
        //     $fail('The :attribute must contain at least one lowercase letter.');
        // }

        // if (!preg_match('/[0-9]/', $value)) {
        //     $fail('The :attribute must contain at least one digit.');
        // }

        // // non-word char or underscore
        // if (!preg_match('/[\W_]/', $value)) { 
        //     $fail('The :attribute must contain at least one special character.');
        // }
    }
}

