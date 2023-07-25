<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    function areas()
    {
        return $this->hasMany(Area::class, 'city_name', 'name');
    }

    function zones()
    {
        return $this->hasMany(Zone::class, 'mapped_zone', 'name');
    }
}
