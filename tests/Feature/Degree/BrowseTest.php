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

    /** @test */
    public function testRequiresUserToBeLoggedIn()
    {
        $response = $this->json('GET', '/api/degrees');

        $response->assertStatus(401);
    }
}
