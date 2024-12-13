<?php

declare(strict_types=1);

namespace BenefitsMe\ApiAuth\Services;

use BenefitsMe\ApiAuth\Exceptions\FailedRequestException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class AuthService
{
    protected string $apiBaseUrl;

    protected string $apiVersion;

    public function __construct()
    {
        $this->apiBaseUrl = config('api-auth.url');
        $this->apiVersion = config('api-auth.version');
    }

    private function buildHeader(array $headers = []): array
    {
        return array_merge([
            'Accept' => 'application/json',
            'X-API-Version' => $this->apiVersion,
        ], $headers);
    }

    private function httpClient(
        string|null $token = null,
        array $headers = [],
    ): PendingRequest
    {
        $request = Http::withHeaders($this->buildHeader($headers));

        if (null !== $token) {
            $request->withToken($token);
        }

        return $request;
    }

    private function url(string $path): string
    {
        return $this->apiBaseUrl . $path;
    }

    /**
     * @throws ConnectionException
     * @throws FailedRequestException
     */
    public function login(string $login, string $password): array
    {
        $response = $this->httpClient()
            ->post($this->url('/login'), [
                'login' => $login,
                'password' => $password,
            ]);

        if ( ! $response->successful()) {
            throw new FailedRequestException('Login failed!');
        }

        return $response->json();
    }

    /**
     * @throws FailedRequestException
     * @throws ConnectionException
     */
    public function register(
        string $loginWith,
        string $login,
        string $password,
        int $companyId,
        int $regionId,
    ): array
    {
        $response = $this->httpClient()
            ->post($this->url('/register'), [
                'login_with' => $loginWith,
                'login_email' => $login,
                'password' => $password,
                'company_id' => $companyId,
                'region_id' => $regionId,
            ]);

        if ( ! $response->successful()) {
            throw new FailedRequestException('Registration failed!');
        }

        return $response->json();
    }

    /**
     * @throws ConnectionException
     */
    public function validateToken(string $token): bool
    {
        $response = $this->httpClient($token)
            ->get($this->url('/validate-token'));

        return $response->successful();
    }
}