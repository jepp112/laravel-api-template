<?php

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('will login user', function () {
    $this->postJson('api/auth/login', [
        'email' => $this->user->email,
        'password' => 'password'
    ])
        ->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type'
        ]);

    $this->assertAuthenticated();
});

it('will logout user', function () {

    $token = JWTAuth::fromUser($this->user);

    $this->postJson('api/auth/logout?token=' . $token)
        ->assertStatus(200)
        ->assertExactJson(['message' => 'Successfully logged out']);
});

it('will refresh token', function () {

    $token = JWTAuth::fromUser($this->user);

    $this->post('api/auth/refresh?token=' . $token)
        ->assertStatus(200)
        ->assertJsonStructure([
            'access_token',
            'token_type'
        ]);
});



