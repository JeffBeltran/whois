<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testUserCanBrowseUser()
    {
        $this->withoutExceptionHandling();

        factory(User::class, 4)->create();

        $response = $this->actingAs(
            factory(User::class)->create(),
            'sanctum'
        )->json('GET', '/api/users');

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /**
     * @test
     * @dataProvider allowedRelationships
     * */
    public function testCanGetRelations($relationName)
    {
        $this->withoutExceptionHandling();

        $response = $this->actingAs(
            factory(User::class)->create(),
            'sanctum'
        )->json('GET', "/api/users?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'profile relationship allowed' => ['profile'],
        ];
    }

    /** @test */
    public function testRequiresUserToBeLoggedIn()
    {
        $response = $this->json('GET', '/api/users');

        $response->assertStatus(401);
    }
}
