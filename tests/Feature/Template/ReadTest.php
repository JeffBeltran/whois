<?php

namespace Tests\Feature\Model;

use App\User;
use App\Model;
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
            )->json('GET', "/api/models/$id" . $params);
        } else {
            return $this->json('GET', "/api/models/$id" . $params);
        }
    }

    /** @test */
    public function testReturnsModelDetailsForTheGivenId()
    {
        $this->withoutExceptionHandling();

        $model = factory(Model::class)->create();

        $response = $this->readModel($model->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $model->id,
            'name' => $model->name,
            ...
            'relationship_id' => $model->relationship_id,
            'created_at' => $model->created_at->toISOString(),
            'updated_at' => $model->updated_at->toISOString(),
        ]);
    }

    /**
     * @test
     * @dataProvider allowedRelationships
     * */
    public function testCanGetRelations($relationName)
    {
        $this->withoutExceptionHandling();

        $model = factory(Model::class)->create();

        $response = $this->readModel($model->id, "?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'modelRelationship relationship allowed' => ['modelRelationship'],
        ];
    }

    /** @test */
    public function testModelMustHaveReadPermissionToViewDetails()
    {
        $user = factory(Model::class)->create();

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
