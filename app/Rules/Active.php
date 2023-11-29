<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Active implements ValidationRule
{
    private string $class;
    private string $keyColumn;
    private string $activeColumn;

    public function __construct(string $class, string $keyColumn = 'id', string $activeColumn = 'active')
    {
        $this->class = $class;
        $this->keyColumn = $keyColumn;
        $this->activeColumn = $activeColumn;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string  $attribute
     * @param mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return !$value || $this->class::where($this->keyColumn, $value)->where($this->activeColumn, true)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "The selected value is inactive";
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->passes($attribute, $value)) $fail($this->message());
    }
}
