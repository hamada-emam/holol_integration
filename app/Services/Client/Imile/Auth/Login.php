<?php

namespace App\Services\Client\Imile\Auth;

use Illuminate\Support\Facades\Http;

class Login
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
    static function login(): string
    {
        $body = [
            "customerId" => "C21018520", # dev 
            // "customerId" => "C2103720301",
            "Sign" => "MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBAN2lOq+RJdIifbPL", # dev 
            // "Sign" => "MIICdQIBADANBgkqhkiG9w0BAQEFAASCAl8wggJbAgEAAoGBAKqGjysoxJtCHFgU",
            "signMethod" => "SimpleKey",
            'format' => 'json',
            'version' => '1.0.0',
            'timestamp' => '1647727143355',
            'timeZone' => '+8',
            'param' => [
                'grantType' => 'clientCredential',
            ],
        ];

        // $response = Http::post('https://openapi.imile.com/auth/accessToken/grant', $body);
        $response = Http::post('https://openapi.52imile.cn/auth/accessToken/grant', $body);
        $responseBody = $response->json();
        return $responseBody['data']['accessToken'];
    }
}
