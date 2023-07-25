<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{

    function updateWithOrders($orderCode)
    {
        $this->order_code = $orderCode;
        $this->save();
    }
}
