<?php

namespace Tests\Feature\Job;

use App\User;
use App\Job;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private function readJob($id, $params = null, $loggedIn = true)
    {
        if ($loggedIn) {
            return $this->actingAs(
                factory(User::class)->create(),
                'sanctum'
            )->json('GET', "/api/jobs/$id" . $params);
        } else {
            return $this->json('GET', "/api/jobs/$id" . $params);
        }
    }

    /** @test */
    public function testReturnsJobDetailsForTheGivenId()
    {
        $this->withoutExceptionHandling();

        $job = factory(Job::class)->create();

        $response = $this->readJob($job->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $job->id,
            'title' => $job->title,
            'description' => $job->description,
            'start' => $job->start,
            'end' => $job->end,
            'company_id' => $job->company_id,
            'created_at' => $job->created_at->toISOString(),
            'updated_at' => $job->updated_at->toISOString(),
        ]);
    }

    /**
     * @test
     * @dataProvider allowedRelationships
     * */
    public function testCanGetRelations($relationName)
    {
        $this->withoutExceptionHandling();

        $job = factory(Job::class)->create();

        $response = $this->readJob($job->id, "?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'company relationship allowed' => ['company'],
            'highlights relationship allowed' => ['highlights'],
        ];
    }

    /** @test */
    public function testJobMustHaveReadPermissionToViewDetails()
    {
        $user = factory(Job::class)->create();

        $response = $this->readJob($user->id, '', false);

        $response->assertStatus(401);
    }

    /** @test */
    public function testReturns404ErrorIfNoJobExists()
    {
        $response = $this->readJob(22);

        $response->assertStatus(404);
    }
}
