<?php

namespace Tests\Feature\Degree;

use App\User;
use App\Degree;
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
            )->json('GET', "/api/degrees/$id" . $params);
        } else {
            return $this->json('GET', "/api/degrees/$id" . $params);
        }
    }

    /** @test */
    public function testReturnsDegreeDetailsForTheGivenId()
    {
        $this->withoutExceptionHandling();

        $degree = factory(Degree::class)->create();

        $response = $this->readModel($degree->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $degree->id,
            'level' => $degree->level,
            'field' => $degree->field,
            'specialty' => $degree->specialty,
            'graduation' => $degree->graduation,
            'institution_id' => $degree->institution_id,
            'created_at' => $degree->created_at->toISOString(),
            'updated_at' => $degree->updated_at->toISOString(),
        ]);
    }

    /**
     * @test
     * @dataProvider belongsToRelationships
     * */
    public function testReturnsBelongsToRelationship(
        $modelNameSpace,
        $relationKey,
        $relationName
    ) {
        $this->withoutExceptionHandling();

        $relationModel = factory($modelNameSpace)->create();
        $degree = factory(Degree::class)->create([
            $relationKey => $relationModel->id,
        ]);

        $response = $this->readModel($degree->id, "?include=$relationName");

        $response->assertStatus(200)->assertJsonStructure(["$relationName"]);
        $this->assertNotNull($response["$relationName"]);
    }

    public function belongsToRelationships()
    {
        return [
            'institution relationship' => [
                Institution::class,
                'institution_id',
                'institution',
            ],
        ];
    }

    /** @test */
    public function testDegreeMustHaveReadPermissionToViewDetails()
    {
        $user = factory(Degree::class)->create();

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
