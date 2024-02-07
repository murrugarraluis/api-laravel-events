<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    public function setUp(): void
    {
        parent::setUp();
        $this->seedData();
    }

    public function seedData(): void
    {
        User::factory()->create([
            'email' => 'admin@app.com',
            'password' => bcrypt('123456')
        ]);
    }

    public function test_login()
    {
        $payload = [
            'email' => 'admin@app.com',
            'password' => '123456'
        ];
        $response = $this->postJson('api/v1/login', $payload);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email'
                ],
                'token',
            ]);
    }
    public function test_login_invalid()
    {
        $payload = [
            'email' => 'admin@app.com',
            'password' => '123456789'
        ];
        $response = $this->postJson('api/v1/login', $payload);
        $response
            ->assertStatus(401)
            ->assertJson(['message' => 'Invalid Credentials.']);
    }
}
