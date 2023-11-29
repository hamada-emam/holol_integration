<?php

namespace App\Models;

use App\Traits\GenerateCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property bool $active
 */
class Provider extends Model
{
    use GenerateCode;

    /**
     * Interact with the user's first name.
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            // get: fn (string $value) => ucfirst($value),
            set: fn ($value) => $value ?? $this->generateCode(),
        );
    }

    function scopeActive($query, $active = true)
    {
        return $query->where('active', $active);
    }
}
