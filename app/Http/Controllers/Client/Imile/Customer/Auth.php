<?php

namespace App\Http\Controllers\Client\Imile\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class Auth extends Controller
{
    /**
     * return token 
     *
     * @return string
     */
    static function auth(): string
    {
        $body = [
            'customerId' => 'C2103720301',
            'sign' => 'MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKqGjysoxJtCHFgU',
            'signMethod' => 'SimpleKey',
            'format' => 'json',
            'version' => '1.0.0',
            'timestamp' => '1647727143355',
            'timeZone' => '+8',
            'param' => [
                'grantType' => 'clientCredential',
            ],
        ];

        $response = Http::post('https://openapi.imile.com/auth/accessToken/grant', $body); #production
        // $response = Http::post('https://openapi.52imile.cn/auth/accessToken/grant', $body);#dev

        $responseBody = $response->json();
        return $responseBody['data']['accessToken'];
    }
}
