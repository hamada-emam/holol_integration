<?php

namespace App\Enums;

enum  OrderTypeCode
{
        // Status Code	Description
    const RECEIVE = '100';               # FDP       #Receive 
    const SHIPPING = '200';              # RTS      #Shipping
    const ARRIVE = '300';                # PTP      #Arrive

    // case ASSIGN_DA = '400';             #       #Assign DA
    // case OUT_FOR_DELIVERY = '500';      #       #Out For Delivery
    // case RETURN_ARRIVE = '600';         #       #Return Arrive

}
