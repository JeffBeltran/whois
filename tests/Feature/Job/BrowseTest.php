<?php

namespace Tests\Feature\Job;

use App\User;
use App\Job;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testUserCanBrowseJob()
    {
        $this->withoutExceptionHandling();

        factory(Job::class, 5)->create();

        $response = $this->actingAs(
            factory(User::class)->create(),
            'sanctum'
        )->json('GET', '/api/jobs');

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
        )->json('GET', "/api/jobs?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'company relationship allowed' => ['company'],
            'highlights relationship allowed' => ['highlights'],
        ];
    }

    /** @test */
    public function testRequiresUserToBeLoggedIn()
    {
        $response = $this->json('GET', '/api/jobs');

        $response->assertStatus(401);
    }
}
