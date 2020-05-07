<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'user_id' => 42,
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'city' => $faker->city(),
        'state' => $faker->stateAbbr(),
    ];
});
