<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Degree;
use App\Institution;
use Faker\Generator as Faker;

$factory->define(Degree::class, function (Faker $faker) {
    return [
        'level' => $faker->word,
        'field' => $faker->word,
        'specialty' => $faker->optional()->word,
        'graduation' => $faker->dateTimeThisDecade(),
        'institution_id' => factory(Institution::class),
    ];
});
