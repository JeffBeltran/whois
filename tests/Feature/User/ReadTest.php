<?php

namespace Tests\Feature\User;

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
            )->json('GET', "/api/users/$id" . $params);
        } else {
            return $this->json('GET', "/api/users/$id" . $params);
        }
    }

    /** @test */
    public function testReturnsUserDetailsForTheGivenId()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->readModel($user->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $user->id,
            'email' => $user->email,
            'created_at' => $user->created_at->toISOString(),
            'updated_at' => $user->updated_at->toISOString(),
        ]);
    }

    /**
     * @test
     * @dataProvider allowedRelationships
     * */
    public function testCanGetRelations($relationName)
    {
        $this->withoutExceptionHandling();

        $skill = factory(User::class)->create();

        $response = $this->readModel($skill->id, "?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'profile relationship allowed' => ['profile'],
        ];
    }

    /** @test */
    public function testUserMustHaveReadPermissionToViewDetails()
    {
        $user = factory(User::class)->create();

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
