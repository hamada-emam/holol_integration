<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Equal implements ValidationRule
{

    protected $value;

    /**
     * Create a new in rule instance.
     *
     * @param  array  $values
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
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
        return $value == $this->value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('core/errors.NOT_EQUAL_TO', ['value' => $this->value]);
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->passes($attribute, $value)) $fail($this->message());
    }
}
