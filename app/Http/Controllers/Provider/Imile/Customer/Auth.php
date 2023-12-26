<?php

namespace App\Http\Controllers\Provider\Imile\Customer;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Http;

class Auth extends Controller
{
    /**
     * return token 
     *
     *  -- dev --
     * "customerId" => "C21018520", # TODO get it from global place 
     * "Sign" => "MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAN2lOq+RJdIifbPL", # TODO get it from global place 
     * $response = Http::post('https://openapi.52imile.cn/auth/accessToken/grant', $body); #dev
     * 
     * @return string
     */
    static function auth(): string
    {
        $body = [
            "customerId" => "C2103720301",
            "Sign" => "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKqGjysoxJtCHFgU",
            "signMethod" => "SimpleKey",
            'format' => 'json',
            'version' => '1.0.0',
            'timestamp' => '1647727143355',
            'timeZone' => '+8',
            'param' => [
                'grantType' => 'clientCredential',
            ],
        ];

        $response = Http::post('https://openapi.imile.com/auth/accessToken/grant', $body);

        $responseBody = $response->json();
        return $responseBody['data']['accessToken'];
    }
}
