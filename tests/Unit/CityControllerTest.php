<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\CitySeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    use RefreshDatabase;
    private string $path = 'cities';
    private array $response = [
        'id',
        'name'
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->seedData();
    }

    public function seedData(): void
    {
        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@app.com',
            'password' => bcrypt('123456')
        ])->assignRole('admin');
        $this->seed(CitySeeder::class);
    }


    /**
     * A basic unit test example.
     */
    public function test_index(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->getJson("api/v1/$this->path");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [
                '*' => $this->response
            ]]);
    }

    public function test_show(): void
    {
        $user = User::first();
        $city = City::first();

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->getJson("api/v1/$this->path/$city->id");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => $this->response]);
    }

    public function test_store(): void
    {
        $user = User::first();
        $payload = [
            'name' => 'city 1'
        ];

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->postJson("api/v1/$this->path", $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => $this->response])
            ->assertJsonFragment(['message' => 'City created.']);

    }

    public function test_update(): void
    {
        $user = User::first();
        $payload = [
            'name' => 'city 1'
        ];
        $city = City::first();

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->putJson("api/v1/$this->path/$city->id", $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => $this->response])
            ->assertJsonFragment(['message' => 'City updated.']);

    }

    public function test_show_not_found(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->getJson("api/v1/$this->path/10000");

        $response->assertStatus(404)
            ->assertExactJson(['message' => 'City not found.']);
    }

    public function test_destroy(): void
    {
        $user = User::first();
        $city = City::first();

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->deleteJson("api/v1/$this->path/$city->id");

        $response->assertStatus(200)
            ->assertExactJson(["message" => 'City deleted.']);

        $this->assertSoftDeleted('cities', ['id' => $city->id]);
    }
}
