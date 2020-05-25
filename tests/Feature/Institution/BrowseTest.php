<?php

namespace Tests\Feature\Institution;

use App\User;
use App\Institution;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function testUserCanBrowseInstitutions()
    {
        $this->withoutExceptionHandling();

        factory(Institution::class, 5)->create();

        $response = $this->actingAs(
            factory(User::class)->create(),
            'sanctum'
        )->json('GET', '/api/institutions');

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /** @test */
    public function testRequiresUserToBeLoggedIn()
    {
        $response = $this->json('GET', '/api/institutions');

        $response->assertStatus(401);
    }
}
