<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;
    public $timestamps = false;

    function city()
    {
        return $this->belongsTo(City::class, 'mapped_zone', 'name');
    }
}
