<?php

namespace Tests\Feature\Profile;

use App\User;
use App\Profile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private function readModel($id, $params = null, $loggedIn = true)
    {
        if ($loggedIn) {
            return $this->actingAs(
                factory(User::class)->create(),
                'sanctum'
            )->json('GET', "/api/profiles/$id" . $params);
        } else {
            return $this->json('GET', "/api/profiles/$id" . $params);
        }
    }

    /** @test */
    public function testReturnsProfileDetailsForTheGivenId()
    {
        $this->withoutExceptionHandling();

        $profile = factory(Profile::class)->create();

        $response = $this->readModel($profile->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $profile->id,
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'city' => $profile->city,
            'state' => $profile->state,
            'user_id' => $profile->user_id,
            'created_at' => $profile->created_at->toISOString(),
            'updated_at' => $profile->updated_at->toISOString(),
        ]);
    }

    /**
     * @test
     * @dataProvider allowedRelationships
     * */
    public function testCanGetRelations($relationName)
    {
        $this->withoutExceptionHandling();

        $skill = factory(Profile::class)->create();

        $response = $this->readModel($skill->id, "?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'user relationship allowed' => ['user'],
        ];
    }

    /** @test */
    public function testProfileMustHaveReadPermissionToViewDetails()
    {
        $user = factory(Profile::class)->create();

        $response = $this->readModel($user->id, '', false);

        $response->assertStatus(401);
    }

    /** @test */
    public function testReturns404ErrorIfNoModelExists()
    {
        $response = $this->readModel(22);

        $response->assertStatus(404);
    }
}
