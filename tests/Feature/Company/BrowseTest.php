<?php

namespace Tests\Feature\Company;

use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testUserCanBrowseCompany()
    {
        $this->withoutExceptionHandling();

        factory(Company::class, 5)->create();

        $response = $this->actingAs(
            factory(User::class)->create(),
            'sanctum'
        )->json('GET', '/api/companies');

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
        )->json('GET', "/api/companies?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'jobs relationship allowed' => ['jobs'],
        ];
    }

    /** @test */
    public function testRequiresUserToBeLoggedIn()
    {
        $response = $this->json('GET', '/api/companies');

        $response->assertStatus(401);
    }
}
