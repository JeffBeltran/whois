<?php

namespace Tests\Feature\Degree;

use App\User;
use App\Degree;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testUserCanBrowseDegrees()
    {
        $this->withoutExceptionHandling();

        factory(Degree::class, 5)->create();

        $response = $this->actingAs(
            factory(User::class)->create(),
            'sanctum'
        )->json('GET', '/api/degrees');

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
        )->json('GET', "/api/degrees?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'institution relationship allowed' => ['institution'],
        ];
    }

    /** @test */
    public function testRequiresUserToBeLoggedIn()
    {
        $response = $this->json('GET', '/api/degrees');

        $response->assertStatus(401);
    }
}
