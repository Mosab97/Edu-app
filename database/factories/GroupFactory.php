<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\Group::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'price' => $faker->numberBetween(100, 1000),
        'students_number_max' => $faker->numberBetween(5, 30),
        'number_of_live_lessons' => $faker->numberBetween(5, 30),
        'number_of_exercises_and_games' => $faker->numberBetween(5, 30),
        'course_date_and_time' => $faker->dateTime,
//        \Carbon\Carbon::now()
        'what_will_i_learn' => $faker->sentence,
        'gender' => collect(Gender)->random(),
        'teacher_id' => function () {
            return factory(\App\Models\User::class)->create()->id;
        },
        'course_id' => function () {
            return factory(\App\Models\Course::class)->create()->id;
        },
        'level_id' => function () {
            return factory(\App\Models\Level::class)->create()->id;
        },
        'age_id' => function () {
            return factory(\App\Models\Age::class)->create()->id;
        },
    ];
});
