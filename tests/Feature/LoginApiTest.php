<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    public function testEmailFieldRequired()
    {
        $userData = [
            "password" => "johndoe",
        ];
        $this->json('POST', 'api/login', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "Email is required"
            ]);
    }

    public function testPasswordFieldRequired()
    {
        $userData = [
            "email" => "john@doe",
        ];
        $this->json('POST', 'api/login', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "Password is required"
            ]);
    }

    public function testValidEmail(){
        $userData = [
            "email" => 'not an email',
            "password" => "password",
        ];

        $this->json('POST', 'api/login', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "Invalid email address"
        ]);
    }

    public function testSuccessfulLogin()
    {
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
        ]);


        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure([
                "access_token",
        ]);
    }

    public function testInvalidCredentials()
    {
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
         ]);


        $loginData = ['email' => 'random@email.com', 'password' => 'sample123'];

        $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                "message" => "Invalid credentials"
        ]);
    }
}
