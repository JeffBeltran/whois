<?php

namespace Tests\Feature\Profile;

use App\User;
use App\Profile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testUserCanBrowseProfile()
    {
        $this->withoutExceptionHandling();

        factory(Profile::class, 5)->create();

        $response = $this->actingAs(
            factory(User::class)->create(),
            'sanctum'
        )->json('GET', '/api/profiles');

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
        )->json('GET', "/api/profiles?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'user relationship allowed' => ['user'],
        ];
    }

    /** @test */
    public function testRequiresUserToBeLoggedIn()
    {
        $response = $this->json('GET', '/api/profiles');

        $response->assertStatus(401);
    }
}
