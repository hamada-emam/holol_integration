<?php

namespace App\Enums;

enum  UserTypeCode: string
{
    case DELIVERY_AGENT = "DLV";  # delivery agent 
    case CUSTOMER = "CUSTM";      # customer
}
