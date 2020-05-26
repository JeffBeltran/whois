<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Highlight;
use Faker\Generator as Faker;

$factory->define(Highlight::class, function (Faker $faker) {
    return [
        'description' => $faker->text(),
        'job_id' => 42,
    ];
});
