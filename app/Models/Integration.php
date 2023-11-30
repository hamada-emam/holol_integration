<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property Barcode $barcode
 * @method static Builder ordered()
 * @property int $pendingCollectionAmount

 */
class Integration extends Model
{
    function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Interact with the user's first name.
     */
    protected function userId(): Attribute
    {
        /** @var User */
        $user = auth()->user();
        return Attribute::make(
            // get: fn (string $value) => ucfirst($value),
            set: fn ($value) => ($value && !$user->isAdmin) ? $user->id : $value,
        );
    }
}
