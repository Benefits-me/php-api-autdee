<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;
use BenefitsMe\ApiAuth\Services\AuthService;

it('can one user log in', function () {

    $apiBaseUrl = config('api-auth.url');
    $expiresAt = Carbon\Carbon::now()->addMinutes(10);
    $result = [
        'token' => '99|4kefE4dIocPVg49nWP8iS1zOkYmOes79V5XGMF3P38c97f84',
        'type' => 'bearer',
        'name' => 'Test Token',
        'expires_at' => [
            'datetime' => $expiresAt->toISOString(),
            'string' => $expiresAt->toDayDateTimeString(),
            'human' => $expiresAt->diffForHumans(),
        ]
    ];

    Http::fake([
        $apiBaseUrl . '/login' => Http::response($result, 201),
    ]);

    $service = new AuthService();
    $response = $service->login('super.admin@localhost', 'password');

    expect($response)->toBe($result);
});