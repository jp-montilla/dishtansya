<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterApiTest extends TestCase
{
    use RefreshDatabase;

    public function testEmailFieldRequired()
    {
        $userData = [
            "password" => "johndoe",
        ];
        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
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
        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "Password is required"
            ]);
    }

    public function testSuccessfullRegistration(){
        $userData = [
            "email" => 'email@email.com',
            "password" => "password",
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                "message" => "User successfully registered"
            ]);
    }

    public function testEmailAlreadyTaken(){
        $user = User::factory()->create();
        $userData = [
            "email" => $user->email,
            "password" => "password",
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "Email already taken"
        ]);
    }

    public function testValidEmail(){
        $userData = [
            "email" => 'not an email',
            "password" => "password",
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "The email must be a valid email address."
        ]);
    }
}
