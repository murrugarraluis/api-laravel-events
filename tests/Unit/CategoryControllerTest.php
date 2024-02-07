<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $path = 'categories';
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
        User::factory()->create();
        $this->seed(CategorySeeder::class);
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
        $category = Category::first();

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->getJson("api/v1/$this->path/$category->id");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => $this->response]);
    }

    public function test_store(): void
    {
        $user = User::first();
        $payload = [
            'name' => 'category 1'
        ];

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->postJson("api/v1/$this->path", $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => $this->response])
            ->assertJsonFragment(['message' => 'Category created.']);

    }

    public function test_update(): void
    {
        $user = User::first();
        $payload = [
            'name' => 'category 1'
        ];
        $category = Category::first();

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->putJson("api/v1/$this->path/$category->id", $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => $this->response])
            ->assertJsonFragment(['message' => 'Category updated.']);

    }

    public function test_show_not_found(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->getJson("api/v1/$this->path/10000");

        $response->assertStatus(404)
            ->assertExactJson(['message' => 'Category not found.']);
    }

    public function test_destroy(): void
    {
        $user = User::first();
        $category = Category::first();

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->deleteJson("api/v1/$this->path/$category->id");

        $response->assertStatus(200)
            ->assertExactJson(["message" => 'Category deleted.']);

        $this->assertDatabaseMissing('events', ['id' => $category->id]);
    }
}
