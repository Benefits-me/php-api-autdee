<?php

declare(strict_types=1);

namespace BenefitsMe\ApiAuth;

use Illuminate\Support\Facades\Http;

class AuthService
{
    protected string $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api-auth.url');
    }

    public function validateToken(string $token): bool
    {
        $response = Http::withToken($token)
            ->withHEaders([
                'Accept' => 'application/json',
                'X-API-Version' => '1.0.0',
            ])
            ->get($this->apiBaseUrl . '/validate-token');

        return $response->successful();
    }
}