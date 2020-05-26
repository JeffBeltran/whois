<?php

namespace Tests\Feature\Skill;

use App\User;
use App\Skill;
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
            )->json('GET', "/api/skills/$id" . $params);
        } else {
            return $this->json('GET', "/api/skills/$id" . $params);
        }
    }

    /** @test */
    public function testReturnsSkillDetailsForTheGivenId()
    {
        $this->withoutExceptionHandling();

        $skill = factory(Skill::class)->create();

        $response = $this->readModel($skill->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $skill->id,
            'name' => $skill->name,
            'slug' => $skill->slug,
            'website' => $skill->website,
            'note' => $skill->note,
            'parent_id' => $skill->parent_id,
            'created_at' => $skill->created_at->toISOString(),
            'updated_at' => $skill->updated_at->toISOString(),
        ]);
    }

    /**
     * @test
     * @dataProvider allowedRelationships
     * */
    public function testCanGetRelations($relationName)
    {
        $this->withoutExceptionHandling();

        $skill = factory(Skill::class)->create();

        $response = $this->readModel($skill->id, "?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'highlights relationship allowed' => ['highlights'],
            'parent relationship allowed' => ['parent'],
            'children relationship allowed' => ['children'],
        ];
    }

    /** @test */
    public function testSkillMustHaveReadPermissionToViewDetails()
    {
        $user = factory(Skill::class)->create();

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
