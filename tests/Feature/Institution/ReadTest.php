<?php

namespace Tests\Feature\Institution;

use App\User;
use App\Institution;
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
            )->json('GET', "/api/institutions/$id" . $params);
        } else {
            return $this->json('GET', "/api/institutions/$id" . $params);
        }
    }

    /** @test */
    public function testReturnsInstitutionDetailsForTheGivenId()
    {
        $this->withoutExceptionHandling();

        $institution = factory(Institution::class)->create();

        $response = $this->readModel($institution->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $institution->id,
            'name' => $institution->name,
            'city' => $institution->city,
            'state' => $institution->state,
            'created_at' => $institution->created_at->toISOString(),
            'updated_at' => $institution->updated_at->toISOString(),
        ]);
    }

    /** @test */
    public function testInstitutionMustHaveReadPermissionToViewDetails()
    {
        $user = factory(Institution::class)->create();

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
