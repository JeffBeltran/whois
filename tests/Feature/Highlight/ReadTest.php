<?php

namespace Tests\Feature\Highlight;

use App\User;
use App\Highlight;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private function readHighlight($id, $params = null, $loggedIn = true)
    {
        if ($loggedIn) {
            return $this->actingAs(
                factory(User::class)->create(),
                'sanctum'
            )->json('GET', "/api/highlights/$id" . $params);
        } else {
            return $this->json('GET', "/api/highlights/$id" . $params);
        }
    }

    /** @test */
    public function testReturnsHighlightDetailsForTheGivenId()
    {
        $this->withoutExceptionHandling();

        $highlight = factory(Highlight::class)->create();

        $response = $this->readHighlight($highlight->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $highlight->id,
            'description' => $highlight->description,
            'job_id' => $highlight->job_id,
            'created_at' => $highlight->created_at->toISOString(),
            'updated_at' => $highlight->updated_at->toISOString(),
        ]);
    }

    /**
     * @test
     * @dataProvider allowedRelationships
     * */
    public function testCanGetRelations($relationName)
    {
        $this->withoutExceptionHandling();

        $highlight = factory(Highlight::class)->create();

        $response = $this->readHighlight(
            $highlight->id,
            "?include=$relationName"
        );

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'job relationship allowed' => ['job'],
            'skills relationship allowed' => ['skills'],
        ];
    }

    /** @test */
    public function testHighlightMustHaveReadPermissionToViewDetails()
    {
        $user = factory(Highlight::class)->create();

        $response = $this->readHighlight($user->id, '', false);

        $response->assertStatus(401);
    }

    /** @test */
    public function testReturns404ErrorIfNoHighlightExists()
    {
        $response = $this->readHighlight(22);

        $response->assertStatus(404);
    }
}
