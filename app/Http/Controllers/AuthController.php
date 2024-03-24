<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class AuthController extends Controller
{
    public function login()
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'text/plain'
        ];
        $body = '{
            "mail": "m.rupprecht@docshero.de",
            "password": "12345",
            "remember": false
        }';
        $request = new Request('POST', env('VITE_AUTH_SERVICE_URL') . '/login', $headers, $body);
        $res = $client->sendAsync($request)->wait();

        $body = $res->getBody();
        $content = $body->getContents();
        $data = json_decode($content, true); // Convert the JSON string to an array

        return $data['token'];
    }
}
