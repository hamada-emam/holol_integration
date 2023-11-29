<?php

namespace App\Rules\Core;

use InvalidArgumentException;

class NullableIf
{
    /**
     * The condition that validates the attribute.
     *
     * @var callable|bool
     */
    public $condition;

    /**
     * Create a new required validation rule based on a condition.
     *
     * @param callable|bool  $condition
     * @return void
     */
    public function __construct($condition)
    {
        if (!is_bool($condition) && !is_callable($condition))
            throw new InvalidArgumentException('The provided condition must be a callable or boolean.');

        $this->condition = $condition;
    }

    public function __toString() {
        $condition = is_callable($this->condition) ? call_user_func($this->condition) : $this->condition;
        return $condition ? 'nullable' : '';
    }
}
