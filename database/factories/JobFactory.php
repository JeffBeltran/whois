<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Company;
use App\Job;
use Faker\Generator as Faker;

$factory->define(Job::class, function (Faker $faker) {
    return [
        'title' => $faker->jobTitle,
        'description' => $faker->text,
        'city' => $faker->city,
        'state' => $faker->stateAbbr,
        'start' => $faker->dateTimeThisDecade(),
        'end' => $faker->dateTimeThisDecade(),
        'project' => $faker->boolean(),
        'company_id' => factory(Company::class),
    ];
});
