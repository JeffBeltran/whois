<?php

use App\Job;
use App\User;
use App\Company;
use App\Degree;
use App\Highlight;
use App\Institution;
use App\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BasicSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $parentSkills = factory(Skill::class, 10)->create();
        factory(Skill::class, 25)
            ->create()
            ->each(function ($skill) use ($parentSkills) {
                $skill->parent_id = $parentSkills->random()->id;
                $skill->save();
            });

        $allSkills = Skill::all();
        factory(User::class)->create([
            'email' => 'a@a.com',
            'password' => Hash::make('a'),
        ]);
        factory(Institution::class, 10)->create();
        factory(Company::class, 10)->create();
        factory(Job::class, 3)
            ->create()
            ->each(function ($job) use ($allSkills) {
                factory(Highlight::class, rand(1, 7))
                    ->create([
                        'job_id' => $job->id,
                    ])
                    ->each(function ($highlight) use ($allSkills) {
                        $highlight
                            ->skills()
                            ->attach($allSkills->random(rand(0, 5)));
                    });
            });
        factory(Degree::class)->create();
    }
}
