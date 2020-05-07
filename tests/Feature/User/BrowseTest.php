<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    private function browseModels($params = null, $loggedIn = true)
    {
        if ($loggedIn) {
            return $this->actingAs(
                factory(User::class)->create(),
                'sanctum'
            )->json('GET', '/api/users' . $params);
        } else {
            return $this->json('GET', '/api/users' . $params);
        }
    }

    /** @test */
    public function testUserCanBrowseUsers()
    {
        $this->withoutExceptionHandling();

        factory(User::class, 5)->create();

        $response = $this->browseModels();

        $response->assertStatus(200)->assertJsonCount(6);
    }

    /** @test */
    public function testRequiresUserToBeLoggedIn()
    {
        $response = $this->browseModels('', false);

        $response->assertStatus(401);
    }
}
