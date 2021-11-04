<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthenticathedUser()
    {
        $userData = [
            "product_id" => 1,
            "quantity" => 4,
        ];

        $this->json('POST', 'api/order', $userData, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJson([
                "message" => "Unauthenticated."
            ]);
    }


    public function testProductIdFieldRequired()
    {
        $user = User::factory()->create([
            'email' => 'sample@test.com',
            'password' => 'sample123',
        ]);

        $userData = [
            "quantity" => 4,
        ];

        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $response = $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])->decodeResponseJson();
        
        $this->withHeader('Authorization', 'Bearer ' . $response['access_token'])
            ->json('POST', 'api/order', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "Product id is required"
            ]);
    }


    public function testQuantityFieldRequired()
    {
        User::factory()->create([
            'email' => 'sample@test.com',
            'password' => 'sample123',
        ]);

        Product::factory()->create([
            'name' => 'Product 1',
        ]);

        $userData = [
            "product_id" => 1,
        ];

        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $response = $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])->decodeResponseJson();
        $this->withHeader('Authorization', 'Bearer ' . $response['access_token'])
            ->json('POST', 'api/order', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "Quantity is required"
            ]);
    }

    public function testProductNotFound()
    {
        User::factory()->create([
            'email' => 'sample@test.com',
            'password' => 'sample123',
        ]);

        $userData = [
            "product_id" => 1,
            "quantity" => 1,
        ];

        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $response = $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])->decodeResponseJson();
        $this->withHeader('Authorization', 'Bearer ' . $response['access_token'])
            ->json('POST', 'api/order', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "Product not found"
            ]);
    }

    public function testSuccessfullOrder()
    {
        User::factory()->create([
            'email' => 'sample@test.com',
            'password' => 'sample123',
        ]);

        $product = Product::factory()->create([
            'name' => 'Product 1',
            'available_stock' => 10,
        ]);

        $userData = [
            "product_id" => $product->id,
            "quantity" => 1,
        ];

        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $response = $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])->decodeResponseJson();

        $this->withHeader('Authorization', 'Bearer ' . $response['access_token'])
            ->json('POST', 'api/order', $userData, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJson([
                "message" => "You have successfully ordered this product."
            ]);
    }

    public function testOutOfStockProduct()
    {
        User::factory()->create([
            'email' => 'sample@test.com',
            'password' => 'sample123',
        ]);

        $product = Product::factory()->create([
            'name' => 'Product 1',
            'available_stock' => 1,
        ]);

        $userData = [
            "product_id" => $product->id,
            "quantity" => 2,
        ];

        $loginData = ['email' => 'sample@test.com', 'password' => 'sample123'];

        $response = $this->json('POST', 'api/login', $loginData, ['Accept' => 'application/json'])->decodeResponseJson();

        $this->withHeader('Authorization', 'Bearer ' . $response['access_token'])
            ->json('POST', 'api/order', $userData, ['Accept' => 'application/json'])
            ->assertStatus(400)
            ->assertJson([
                "message" => "Failed to order this product due to unavailability of the stock"
            ]);
    }

}
