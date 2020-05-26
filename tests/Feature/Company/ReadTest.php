<?php

namespace Tests\Feature\Company;

use App\User;
use App\Company;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReadTest extends TestCase
{
    use RefreshDatabase;

    private function readCompany($id, $params = null, $loggedIn = true)
    {
        if ($loggedIn) {
            return $this->actingAs(
                factory(User::class)->create(),
                'sanctum'
            )->json('GET', "/api/companies/$id" . $params);
        } else {
            return $this->json('GET', "/api/companies/$id" . $params);
        }
    }

    /** @test */
    public function testReturnsCompanyDetailsForTheGivenId()
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->create();

        $response = $this->readCompany($company->id);

        $response->assertStatus(200)->assertExactJson([
            'id' => $company->id,
            'name' => $company->name,
            'created_at' => $company->created_at->toISOString(),
            'updated_at' => $company->updated_at->toISOString(),
        ]);
    }

    /**
     * @test
     * @dataProvider allowedRelationships
     * */
    public function testCanGetRelations($relationName)
    {
        $this->withoutExceptionHandling();

        $company = factory(Company::class)->create();

        $response = $this->readCompany($company->id, "?include=$relationName");

        $response->assertStatus(200);
    }

    public function allowedRelationships()
    {
        return [
            'jobs relationship allowed' => ['jobs'],
        ];
    }

    /** @test */
    public function testCompanyMustHaveReadPermissionToViewDetails()
    {
        $user = factory(Company::class)->create();

        $response = $this->readCompany($user->id, '', false);

        $response->assertStatus(401);
    }

    /** @test */
    public function testReturns404ErrorIfNoCompanyExists()
    {
        $response = $this->readCompany(22);

        $response->assertStatus(404);
    }
}
