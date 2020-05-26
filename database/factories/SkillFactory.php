<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Skill;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Skill::class, function (Faker $faker) {
    return [
        'name' => $faker->words(rand(1, 4), true),
        'slug' => function (array $skill) {
            return Str::slug($skill['name'], '-');
        },
        'website' => $faker->url,
    ];
});
