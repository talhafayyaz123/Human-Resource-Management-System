<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(env('VITE_AUTH_SERVICE_URL') . '/login', [
            'mail' => 'admin@docshero.de',
            'password' => '12345',
        ]);
        $this->assertSame(200, $response->status());
    }
}
