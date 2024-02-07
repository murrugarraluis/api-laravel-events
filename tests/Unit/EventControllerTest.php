<?php

namespace Tests\Unit;

use App\Models\Event;
use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\EventSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $path = 'events';
    private array $response = [
        'id',
        'name',
        'slug',
        'content',
        'description',
        'poster',
        'date',
        'time',
        'category' => [
            'id',
            'name',
        ],
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
        $this->seed(CategorySeeder::class);
        $this->seed(EventSeeder::class);
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
        $event = Event::first();

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->getJson("api/v1/$this->path/$event->id");

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => $this->response]);
    }

    public function test_store(): void
    {
        $user = User::first();
        $payload = [
            'name' => 'name 1',
            'content' => 'content 1',
            'description' => 'description 1',
            'poster' => 'https://www.google.com',
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
            'category' => [
                'id' => 1
            ],

        ];

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->postJson("api/v1/$this->path", $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => $this->response])
            ->assertJsonFragment(['message' => 'Event created.']);

    }

    public function test_update(): void
    {
        $user = User::first();
        $payload = [
            'name' => 'name 1',
            'content' => 'content 1',
            'description' => 'description 1',
            'poster' => 'https://www.google.com',
            'date' => date('Y-m-d'),
            'time' => date('H:i'),
            'category' => [
                'id' => 1
            ]
        ];
        $event = Event::first();

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->putJson("api/v1/$this->path/$event->id", $payload);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => $this->response])
            ->assertJsonFragment(['message' => 'Event updated.']);

    }

    public function test_show_not_found(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->getJson("api/v1/$this->path/10000");

        $response->assertStatus(404)
            ->assertExactJson(['message' => 'Event not found.']);
    }

    public function test_destroy(): void
    {
        $user = User::first();
        $event = Event::first();

        $response = $this->actingAs($user)->withSession(['banned' => false])
            ->deleteJson("api/v1/$this->path/$event->id");

        $response->assertStatus(200)
            ->assertExactJson(["message" => 'Event deleted.']);

        $this->assertSoftDeleted('events', ['id' => $event->id]);
    }
}
