<?php

namespace App\Enums;

/**
 *
 * const DELIVERED = '1200';   that means delivery is done ? DTR
 * const CANCELED = '2403';   , that mean delivery is canceled ? RTS
 * const RE_SCHEDULE = '302';  , that means that it will be back to redelivery?  HTR
 * 
 */
enum  WebHookStatusCode
{
    // Status Code	Description
    const PICKUP = '2302';                      #Pickup => Pickup done from pickup location.

    const RECEIVE = '100';                      #Receive => The shipment already received by iMile warehouse.
    const SHIPPING = '200';                     #Shipping => The shipment shipping to delivery station.
    const ARRIVE = '300';                       #Arrive => The shipment arrived delivery station or in transit station.

    const SCHEDULE = '301';                     #Schedule => The shipment has been scheduled for delivery.

    const BAG_ARRIVED = '800';                  #Bag Arrived => The shipment arrived delivery station or in transit station.
    const ASSIGN_DA = '400';                    #Assign DA => The shipment assigned to DA, waiting for delivery.

    const OUT_FOR_DELIVERY = '500';             #Out For Delivery => Driver takes the shipment out for delivery.
    const BACK_TO_WAREHOUSE = '1600';           #Back To Warehouse =>Driver failed delivery the shipment to customer and already take the shipment back to warehouse.
    #   => This will be taken again for customer delivery. This is not a terminal status.

    const RETURN_ARRIVE = '600';                #Return Arrive => Shipment returns to return center from delivery station.
    const RETURN_SHIPPING = '700';              #Return Shipping =>Shipment arrived return center. 

    const SUCCESSFULLY_RETURNED = '1300';       #Successfully Returned => Driver got the return shipment from customer.  
    const SUCCESSFULLY_REFUNDED = '1400';       #Successfully Refunded => Driver returns the COD to customer successfully.
    const SHELF_SCAN = '1700';                  #Shelf Scan => Shipment shelved in iMile warehouse. 
    const PULL_OFF_SCAN = '1800';               #Pull Off Scan => Shipment un-shelved from iMile warehouse. 

    const NVENTORY_SCAN = '1900';               #Inventory Scan => Shipment moved in inventory. 
    const ALREADY_FORWARD = '2201';             #Already Forward => Shipment forwarded.

    const UNPACK = '2202';                      #Unpack  => Failed order unpacks completed. 
    const DESTROY = '2203';                     #DESTROY => Failed order destroys completed. 
    const RETURN_TO_CLIENT = '2204';            #Return to Client => Failed order return to client completed.
    const FAIL_RETURN = '2800';                 #Fail return => Failed order return to client pending.

    const TEMPORARY_STORAGE = '2200';           #Temporary storage => Order in temporary storage.
    const  GOODS_LOST = '2401';                 #Abnormal End - Goods Lost => Package lost. 
    const MERCHANT_CANCELLATION = '2402';       #Abnormal End - Merchant Cancellation =>  Order cancelled by the merchant.

    const DELIVERED = '1200';      # **         #Delivered => Shipment delivered to customer.
    const CANCELED = '2403';       # **         #Canceled => Order cancelled.
    const RE_SCHEDULE = '302';     # **         #Re-Schedule => The shipment has been scheduled for delivery again.
}
