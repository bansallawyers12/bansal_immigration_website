<?php
// app/Rules/AgeRule.php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AgeRule implements Rule
{
    public function passes($attribute, $value)
    {
        return $value >= 18 && $value <= 45;
    }

    public function message()
    {
        return 'The age must be between 18 and 45 years.';
    }
}