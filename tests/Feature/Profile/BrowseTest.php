<?php

namespace Tests\Feature\Profile;

use App\User;
use App\Profile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BrowseTest extends TestCase
{
    use RefreshDatabase;

    private function browseModels($params = null, $loggedIn = true)
    {
        if ($loggedIn) {
            return $this->actingAs(
                factory(User::class)->create(),
                'sanctum'
            )->json('GET', '/api/profiles' . $params);
        } else {
            return $this->json('GET', '/api/profiles' . $params);
        }
    }

    /** @test */
    public function testUserCanBrowseProfiles()
    {
        $this->withoutExceptionHandling();

        factory(Profile::class, 5)->create();

        $response = $this->browseModels();

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /** @test */
    public function testUserCanBrowseProfilesWithTheirUserRelation()
    {
        $this->withoutExceptionHandling();

        factory(Profile::class, 5)
            ->make()
            ->each(function ($profile) {
                $profile->user_id = factory(User::class)->create()->id;
                $profile->save();
            });

        $response = $this->browseModels('?include=user');

        $response->assertStatus(200)->assertJsonCount(5);
        foreach ($response->json() as $profile) {
            $this->assertArrayHasKey('user', $profile);
            $this->assertNotNull($profile['user']);
        }
    }

    /** @test */
    public function testRequiresUserToBeLoggedIn()
    {
        $response = $this->browseModels('', false);

        $response->assertStatus(401);
    }
}
