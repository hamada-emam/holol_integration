<?php

namespace App\Traits;

use Accurate\Shipping\Client\Client as ClientClient;
use App\Models\Client;
use Exception;

/**
 * 
 */
trait InitAccurate
{
    /**
     * 
     */
    function initClient($id = null  )
    {
        try {
            $client = Client::first();
            if ($client) ClientClient::init($client->url, ['Authorization' => "Bearer $client->token"]);
        } catch (Exception $e) {
            info($e->getMessage());
        }
    }
}
